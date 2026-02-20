import { useState, useEffect, useRef, useCallback } from 'react';
import client from '../../api/client';
import toast from 'react-hot-toast';

// Load Leaflet CSS dynamically (CDN — no npm install needed)
const LEAFLET_CSS = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css';
const LEAFLET_JS  = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js';

function ensureLeaflet(cb) {
  if (window.L) { cb(); return; }
  // CSS
  if (!document.querySelector(`link[href="${LEAFLET_CSS}"]`)) {
    const link = document.createElement('link');
    link.rel   = 'stylesheet';
    link.href  = LEAFLET_CSS;
    document.head.appendChild(link);
  }
  // JS
  const script = document.createElement('script');
  script.src   = LEAFLET_JS;
  script.onload = cb;
  document.head.appendChild(script);
}

const DEFAULT_LAT = 12.97350;  // Perungalathur
const DEFAULT_LNG = 80.14840;

export default function ShopSettingsPage() {
  const [lat, setLat]         = useState(DEFAULT_LAT);
  const [lng, setLng]         = useState(DEFAULT_LNG);
  const [address, setAddress] = useState('');
  const [search, setSearch]   = useState('');
  const [results, setResults] = useState([]);
  const [saving, setSaving]   = useState(false);
  const [loading, setLoading] = useState(true);

  const mapRef    = useRef(null);   // DOM div ref
  const mapObj    = useRef(null);   // L.Map instance
  const markerRef = useRef(null);   // L.Marker instance

  // Load current settings
  useEffect(() => {
    client.get('/settings').then((res) => {
      // API returns flat array of {key_name, value} objects
      const raw = res.data;
      const list = Array.isArray(raw) ? raw : (raw.data || []);
      const find = (k) => list.find((x) => x.key_name === k)?.value;
      const savedLat = parseFloat(find('shop_dispatch_lat')) || DEFAULT_LAT;
      const savedLng = parseFloat(find('shop_dispatch_lng')) || DEFAULT_LNG;
      setLat(savedLat);
      setLng(savedLng);
      setAddress(find('shop_dispatch_address') || '');
      setLoading(false);
    }).catch(() => { setLoading(false); });
  }, []);

  // Init Leaflet map after settings are loaded
  useEffect(() => {
    if (loading) return;
    ensureLeaflet(() => {
      if (mapObj.current) return; // already initialised
      const L   = window.L;
      const map = L.map(mapRef.current).setView([lat, lng], 14);
      L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
        maxZoom: 19,
      }).addTo(map);

      const marker = L.marker([lat, lng], { draggable: true }).addTo(map);
      marker.on('dragend', () => {
        const pos = marker.getLatLng();
        setLat(parseFloat(pos.lat.toFixed(8)));
        setLng(parseFloat(pos.lng.toFixed(8)));
      });

      map.on('click', (e) => {
        marker.setLatLng(e.latlng);
        setLat(parseFloat(e.latlng.lat.toFixed(8)));
        setLng(parseFloat(e.latlng.lng.toFixed(8)));
      });

      mapObj.current    = map;
      markerRef.current = marker;
    });
  }, [loading]); // eslint-disable-line

  // Keep marker position in sync with lat/lng state
  useEffect(() => {
    if (!markerRef.current) return;
    markerRef.current.setLatLng([lat, lng]);
    mapObj.current.panTo([lat, lng]);
  }, [lat, lng]);

  // Nominatim address search
  const doSearch = useCallback(async () => {
    if (!search.trim()) return;
    try {
      const res = await fetch(
        `https://nominatim.openstreetmap.org/search?format=json&limit=5&countrycodes=in&q=${encodeURIComponent(search)}`,
        { headers: { 'Accept-Language': 'en', 'User-Agent': 'SriSaiMissionAdmin/1.0' } }
      );
      const data = await res.json();
      setResults(data);
    } catch {
      toast.error('Address search failed');
    }
  }, [search]);

  const pickResult = (item) => {
    const la = parseFloat(item.lat);
    const lo = parseFloat(item.lon);
    setLat(la);
    setLng(lo);
    setAddress(item.display_name);
    setSearch('');
    setResults([]);
    if (mapObj.current) mapObj.current.setView([la, lo], 16);
  };

  const handleSave = async () => {
    setSaving(true);
    try {
      await client.put('/settings', {
        settings: {
          shop_dispatch_lat:     String(lat),
          shop_dispatch_lng:     String(lng),
          shop_dispatch_address: address,
        },
      });
      toast.success('Shop location saved');
    } catch {
      toast.error('Failed to save settings');
    } finally {
      setSaving(false);
    }
  };

  if (loading) return <p style={{ color: 'var(--text-light)' }}>Loading...</p>;

  return (
    <div>
      <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '1.5rem' }}>
        <div>
          <h2 style={{ margin: 0 }}>Shop Dispatch Location</h2>
          <p style={{ margin: '4px 0 0', color: 'var(--text-light)', fontSize: '0.88rem' }}>
            Set the origin point used to calculate delivery distances for shop orders.
          </p>
        </div>
        <button className="btn btn-primary" onClick={handleSave} disabled={saving}>
          {saving ? 'Saving...' : 'Save Location'}
        </button>
      </div>

      <div className="card" style={{ padding: '1.5rem' }}>
        {/* Address Search */}
        <div style={{ marginBottom: '1rem' }}>
          <label style={{ display: 'block', fontWeight: 600, marginBottom: '0.4rem' }}>
            Search Address
          </label>
          <div style={{ display: 'flex', gap: '0.5rem' }}>
            <input
              value={search}
              onChange={(e) => setSearch(e.target.value)}
              onKeyDown={(e) => e.key === 'Enter' && doSearch()}
              placeholder="e.g. Perungalathur, Chennai"
              style={{ flex: 1 }}
            />
            <button className="btn btn-outline" onClick={doSearch}>Search</button>
          </div>
          {results.length > 0 && (
            <div style={{
              border: '1px solid var(--border)', borderRadius: 8, background: '#fff',
              boxShadow: '0 4px 16px rgba(0,0,0,0.1)', maxHeight: 200, overflowY: 'auto',
              marginTop: 4, position: 'relative', zIndex: 1000,
            }}>
              {results.map((r) => (
                <div
                  key={r.place_id}
                  onClick={() => pickResult(r)}
                  style={{
                    padding: '10px 14px', fontSize: '0.85rem', cursor: 'pointer',
                    borderBottom: '1px solid var(--border)',
                  }}
                  onMouseOver={(e) => e.currentTarget.style.background = '#f9f5fc'}
                  onMouseOut={(e) => e.currentTarget.style.background = ''}
                >
                  {r.display_name}
                </div>
              ))}
            </div>
          )}
        </div>

        {/* Leaflet Map */}
        <div
          ref={mapRef}
          style={{ height: 380, borderRadius: 10, border: '2px solid var(--border)', overflow: 'hidden', marginBottom: '1rem' }}
        />

        {/* Lat / Lng inputs */}
        <div style={{ display: 'flex', gap: '1rem', marginBottom: '1rem' }}>
          <div className="form-group" style={{ flex: 1 }}>
            <label>Latitude</label>
            <input
              type="number" step="0.00001" value={lat}
              onChange={(e) => setLat(parseFloat(e.target.value) || 0)}
            />
          </div>
          <div className="form-group" style={{ flex: 1 }}>
            <label>Longitude</label>
            <input
              type="number" step="0.00001" value={lng}
              onChange={(e) => setLng(parseFloat(e.target.value) || 0)}
            />
          </div>
        </div>

        {/* Display address label */}
        <div className="form-group">
          <label>Address Label (shown in admin, not on public site)</label>
          <input
            value={address}
            onChange={(e) => setAddress(e.target.value)}
            placeholder="e.g. Perungalathur Athma Sai Temple, Chennai"
          />
        </div>

        <p style={{ fontSize: '0.82rem', color: 'var(--text-light)', margin: 0 }}>
          Tip: Click on the map or drag the marker to set the exact dispatch origin point.
          All delivery distances are calculated straight-line from this point to the customer's chosen location.
        </p>
      </div>
    </div>
  );
}
