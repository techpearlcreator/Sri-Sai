import { useState } from 'react';
import { HiOutlineSearch } from 'react-icons/hi';

export default function SearchBar({ onSearch, placeholder = 'Search...' }) {
  const [value, setValue] = useState('');

  const handleSubmit = (e) => {
    e.preventDefault();
    onSearch(value);
  };

  return (
    <form className="search-bar" onSubmit={handleSubmit}>
      <HiOutlineSearch size={18} className="search-bar__icon" />
      <input
        type="text"
        value={value}
        onChange={(e) => setValue(e.target.value)}
        placeholder={placeholder}
        className="search-bar__input"
      />
      {value && (
        <button type="button" className="search-bar__clear" onClick={() => { setValue(''); onSearch(''); }}>
          &times;
        </button>
      )}
    </form>
  );
}
