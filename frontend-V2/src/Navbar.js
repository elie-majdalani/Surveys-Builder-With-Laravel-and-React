import React, { useEffect, useState } from 'react';
function Navbar({ setIsLoggedIn, isLoggedIn }) {
    const [username, setUsername] = useState('');
    const [password, setPassword] = useState('');

    useEffect(() => {
        const token = localStorage.getItem('token');
        if (token) {
            setIsLoggedIn(true);
        }
    }, [])
    console.log(isLoggedIn)
    const login = async () => {
        try {
            const res = await fetch("http://127.0.0.1:8000/api/auth/login", {
                method: 'POST',
                body: JSON.stringify({
                    username,
                    password
                })
            });
            const data = await res.json();
            debugger
            if (data.status === 'success') {
                debugger;
                localStorage.setItem('token', data.data);
                setIsLoggedIn(true);
            }
        }
        catch (err) {
            console.log(err);
        }
    }
    const logout = async () => {
        try {
            const res = await fetch("http://127.0.0.1:8000/api/auth/logout", { method: 'POST', headers: { 'Authorization': `Bearer ${localStorage.getItem('token')}` } });
            const data = await res.json();
            debugger
            if (data.message === 'Successfully logged out') {
                localStorage.removeItem('token');
                setIsLoggedIn(false);
            }
        }
        catch (err) {
            console.log(err);
        }
    }

    return (
        <div className="navigation-bar">
            <button>Questions</button>

            {isLoggedIn ? <button onClick={logout}>Logout</button> :
                (
                    <>
                        <input type="text" value={username} placeholder="Username" onChange={(e) => { setUsername(e.currentTarget.value) }} />
                        <input type="text" value={password} placeholder="Password" onChange={(e) => { setPassword(e.currentTarget.value) }} />
                        <button onClick={login}>Login</button>
                    </>
                )}
        </div>
    );
}
export default Navbar;