import React, { createContext, useState, useEffect } from 'react';

export const AuthContext = createContext({
    authToken: null,
    setAuthToken: () => {},
    loading: true,
});

export const AuthProvider = ({ children }) => {
    const [authToken, setAuthToken] = useState(null);
    const [loading, setLoading] = useState(true);
    const [pageLoading, setPageLoading] = useState(false); 

    useEffect(() => {
        const savedToken = localStorage.getItem('authToken');
        if (savedToken) {
            setAuthToken(savedToken);
        }
        setLoading(false); // Mark loading as complete
    }, []);

    useEffect(() => {
        const savedToken = localStorage.getItem('authToken');
        if (authToken !== savedToken) {
            localStorage.setItem('authToken', authToken);
        }
    }, [authToken]);

    useEffect(() => {
        const handleStorageChange = (event) => {
            if (event.key === 'authToken') {
                setAuthToken(event.newValue || '');
            }
        };

        window.addEventListener('storage', handleStorageChange);
        return () => window.removeEventListener('storage', handleStorageChange);
    }, []);



    return (
        <AuthContext.Provider value={{ authToken, setAuthToken, loading, pageLoading, setPageLoading}}>
            {children}
        </AuthContext.Provider>
    );
};
