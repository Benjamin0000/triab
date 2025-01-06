import React from 'react';
import ReactDOM from 'react-dom/client';
import App from './components/App'; // Adjust the path as necessary
import { BrowserRouter } from 'react-router-dom';

const Index = ReactDOM.createRoot(document.getElementById('app'));
Index.render(
    <BrowserRouter>
        <React.StrictMode>
            <App />
        </React.StrictMode>
    </BrowserRouter>
);
