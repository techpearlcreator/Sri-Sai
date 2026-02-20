# 📱 Mobile Device Testing Guide - Sri Sai Mission Website

## How to Access Your Website on Physical Mobile Devices

Follow these simple steps to test your website on your smartphone or tablet.

---

## 🔧 Step 1: Find Your Computer's IP Address

### **Method 1: Using Command Prompt (Recommended)**

1. **Open Command Prompt:**
   - Press `Windows + R`
   - Type `cmd`
   - Press Enter

2. **Run this command:**
   ```cmd
   ipconfig
   ```

3. **Find your IP address:**
   - Look for **"Wireless LAN adapter Wi-Fi"** (if using Wi-Fi)
   - OR **"Ethernet adapter"** (if using cable)
   - Find the line that says **"IPv4 Address"**
   - It will look like: `192.168.1.XXX` or `10.0.0.XXX`

**Example Output:**
```
Wireless LAN adapter Wi-Fi:
   IPv4 Address. . . . . . . . . . . : 192.168.1.100
```

Your IP is: **192.168.1.100** (yours will be different)

### **Method 2: Using Windows Settings**

1. Click **Start** → **Settings** (gear icon)
2. Click **Network & Internet**
3. Click **Wi-Fi** (or **Ethernet**)
4. Click on your connected network name
5. Scroll down to **Properties**
6. Find **IPv4 address**

---

## 🔧 Step 2: Configure WampServer to Allow Network Access

### **Option A: Using WampServer Menu (Easy)**

1. **Click on WampServer icon** in system tray (green/orange/red icon)
2. Hover over **Apache**
3. Click on **httpd-vhosts.conf**
4. Find the section for `srisai` (if it exists) OR go to Option B

### **Option B: Edit Apache Config File (Recommended)**

1. **Open file:** `C:\wamp64\bin\apache\apache2.4.XX\conf\extra\httpd-vhosts.conf`
   (Replace XX with your Apache version, e.g., apache2.4.54)

2. **Add this configuration at the end:**

```apache
# Sri Sai Mission - Network Access
<VirtualHost *:80>
    DocumentRoot "C:/wamp64/www/srisai/public"
    ServerName localhost
    ServerAlias 192.168.1.100

    <Directory "C:/wamp64/www/srisai/public">
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

**IMPORTANT:** Replace `192.168.1.100` with YOUR actual IP address from Step 1!

3. **Save the file** (Ctrl+S)

### **Option C: Allow from All (Quick but Less Secure)**

1. **Open file:** `C:\wamp64\bin\apache\apache2.4.XX\conf\httpd.conf`

2. **Find this line:**
   ```apache
   Require local
   ```

3. **Replace with:**
   ```apache
   Require all granted
   ```

4. **Save the file**

---

## 🔧 Step 3: Configure Windows Firewall

### **Allow Apache Through Firewall:**

1. **Open Control Panel**
2. Click **System and Security**
3. Click **Windows Defender Firewall**
4. Click **Allow an app or feature through Windows Defender Firewall** (left sidebar)
5. Click **Change settings** button
6. Find **Apache HTTP Server** in the list
7. **Check both boxes** (Private and Public)
8. If not in list, click **Allow another app...**
   - Browse to: `C:\wamp64\bin\apache\apache2.4.XX\bin\httpd.exe`
   - Click **Add**
9. Click **OK**

### **Alternative: Create Firewall Rule (Advanced)**

1. Press `Windows + R`
2. Type: `wf.msc` and press Enter
3. Click **Inbound Rules** (left panel)
4. Click **New Rule...** (right panel)
5. Choose **Port** → Next
6. Choose **TCP**, Specific local ports: **80** → Next
7. Choose **Allow the connection** → Next
8. Check all boxes (Domain, Private, Public) → Next
9. Name: **WAMP Apache Port 80** → Finish

---

## 🔧 Step 4: Restart Apache

1. **Click WampServer icon** in system tray
2. Hover over **Apache**
3. Click **Service administration**
4. Click **Restart Service**

OR

1. Click **WampServer icon**
2. Click **Restart All Services**

---

## 📱 Step 5: Connect Your Mobile Device

### **Prerequisites:**
✅ Your mobile device and computer are on the **SAME Wi-Fi network**
✅ Apache is running (WampServer icon is **GREEN**)

### **Access the Website:**

1. **Open browser** on your mobile device (Chrome, Safari, etc.)

2. **Type this URL:**
   ```
   http://YOUR-IP-ADDRESS/srisai/public/
   ```

   **Example:** If your IP is `192.168.1.100`, type:
   ```
   http://192.168.1.100/srisai/public/
   ```

3. **Press Go/Enter**

4. **You should see the website!** 🎉

---

## 🧪 Testing Checklist

Once the website loads on your mobile device:

### **Visual Tests:**
- [ ] Logo displays correctly
- [ ] Navigation menu (hamburger icon) appears
- [ ] Hero slider loads and works
- [ ] "Our Activities" section displays properly (4 boxes in 1 column)
- [ ] All images load
- [ ] Text is readable (not too small)
- [ ] No horizontal scrollbar
- [ ] Footer displays correctly

### **Interaction Tests:**
- [ ] Tap hamburger menu → menu opens
- [ ] Swipe right on menu → menu closes
- [ ] Tap on navigation links → pages load
- [ ] Tap "Donate" button → works
- [ ] Form inputs don't zoom screen (iOS)
- [ ] Buttons have good touch targets (easy to tap)
- [ ] Swiper slider swipes smoothly

### **Performance Tests:**
- [ ] Page loads quickly
- [ ] Scrolling is smooth
- [ ] No lag when opening menu
- [ ] Images load progressively (lazy loading)

---

## 🔍 Troubleshooting

### **Problem 1: "Can't reach this page" or "Connection refused"**

**Solutions:**
1. ✅ Verify both devices on same Wi-Fi network
2. ✅ Check IP address is correct
3. ✅ Ensure WampServer is running (green icon)
4. ✅ Check firewall settings (Step 3)
5. ✅ Restart Apache (Step 4)
6. ✅ Try pinging from mobile:
   - On Android: Use **Fing** app
   - On iPhone: Use **Network Analyzer** app

### **Problem 2: "403 Forbidden" Error**

**Solutions:**
1. ✅ Check Apache config (Step 2)
2. ✅ Ensure `Require all granted` is set
3. ✅ Restart Apache

### **Problem 3: WampServer Icon is Orange/Red**

**Solutions:**
1. Click WampServer icon → **Check System**
2. Fix any errors shown
3. Ensure port 80 is not used by another app (Skype, IIS, etc.)

### **Problem 4: Images Don't Load**

**Solutions:**
1. Check image paths in browser DevTools
2. Ensure images exist in `C:\wamp64\www\srisai\public\assets\images\`
3. Clear browser cache on mobile

### **Problem 5: Can't Find IP Address**

**Alternative Method:**
1. Open browser on computer
2. Go to: https://www.whatismyip.com/
3. Look for **"Local IP"** or **"Private IP"**
4. Use that IP address

---

## 📊 Different Testing Scenarios

### **Test on Different Devices:**

**iPhone:**
```
Safari: http://192.168.1.100/srisai/public/
Chrome: http://192.168.1.100/srisai/public/
```

**Android:**
```
Chrome: http://192.168.1.100/srisai/public/
Firefox: http://192.168.1.100/srisai/public/
Samsung Internet: http://192.168.1.100/srisai/public/
```

**iPad/Tablet:**
```
Safari/Chrome: http://192.168.1.100/srisai/public/
```

### **Test Different Orientations:**
- ✅ Portrait mode (vertical)
- ✅ Landscape mode (horizontal)

### **Test Different Actions:**
- ✅ Tap links
- ✅ Fill forms
- ✅ Swipe sliders
- ✅ Pinch to zoom (should be limited)
- ✅ Scroll pages

---

## 🎥 Screen Recording for Testing

### **iPhone:**
1. Go to **Settings** → **Control Center**
2. Add **Screen Recording**
3. Swipe down from top-right
4. Tap **Record** button
5. Navigate website
6. Stop recording
7. Video saved to Photos

### **Android:**
1. Swipe down twice from top
2. Find **Screen Record** tile
3. Tap to start recording
4. Navigate website
5. Tap notification to stop
6. Video saved to Gallery

---

## 📸 Screenshot Issues for Review

If you find any issues:

1. **Take screenshot:**
   - iPhone: Press **Power + Volume Up**
   - Android: Press **Power + Volume Down**

2. **Note the issue:**
   - Screen size
   - Browser used
   - What's broken

3. **Share for fixing**

---

## ✅ Quick Reference Card

```
┌─────────────────────────────────────────┐
│  MOBILE TESTING QUICK START             │
├─────────────────────────────────────────┤
│  1. Get IP: Run 'ipconfig' in CMD       │
│     Look for: 192.168.X.XXX             │
│                                          │
│  2. Edit Apache config:                 │
│     C:\wamp64\bin\apache\apache2.4.XX\  │
│     conf\extra\httpd-vhosts.conf        │
│                                          │
│  3. Allow firewall (port 80)            │
│                                          │
│  4. Restart Apache (WampServer)         │
│                                          │
│  5. On mobile browser:                  │
│     http://YOUR-IP/srisai/public/       │
│                                          │
│  ✅ Same Wi-Fi network required!        │
└─────────────────────────────────────────┘
```

---

## 🔗 Useful Apps for Testing

### **Network Tools:**
- **Fing** (Android/iOS) - Find devices on network
- **Network Analyzer** (iOS) - Network diagnostics
- **WiFi Analyzer** (Android) - Network info

### **Browser Testing:**
- **Chrome** (Android/iOS)
- **Safari** (iOS)
- **Firefox** (Android/iOS)
- **Samsung Internet** (Android)
- **Edge** (Android/iOS)

### **Screen Size Testing:**
- **Responsively** - Multi-device preview app
- **BrowserStack** - Real device testing (paid)

---

## 🎯 Summary

**To test on mobile device:**

1. ✅ Find computer IP address (`ipconfig`)
2. ✅ Configure Apache to allow network access
3. ✅ Allow Apache through Windows Firewall
4. ✅ Restart Apache/WampServer
5. ✅ Connect mobile to same Wi-Fi
6. ✅ Open `http://YOUR-IP/srisai/public/` on mobile

**Expected Result:**
Your website should load perfectly on your mobile device with all responsive features working!

---

**Need Help?**
- Check WampServer is GREEN
- Verify both devices on same Wi-Fi
- Double-check IP address
- Restart Apache

Happy Testing! 📱✨
