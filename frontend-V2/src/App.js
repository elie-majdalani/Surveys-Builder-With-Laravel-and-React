import './App.css';
import Questions from './Questions';
import Admin from './Admin';
import Navbar from './Navbar';
import React, { useState } from 'react';
function App() {
  const [reload, setReload] = useState(true);
  const [isLoggedIn, setIsLoggedIn] = useState(false);
  return (
    <div id="body">
      <div className="title">hey there</div>
      <Navbar setIsLoggedIn={setIsLoggedIn} isLoggedIn={isLoggedIn} />
      <Questions reload={reload} setReload={setReload} isLoggedIn={isLoggedIn} />
      {isLoggedIn && <Admin setReload={setReload} />}
    </div>
  )
}
export default App;