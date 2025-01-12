import { Routes, Route } from "react-router-dom";
import { CartProvider } from "./context/CartContext";
import { AuthProvider } from "./context/AuthContext"; 
import { ToastContainer } from "react-toastify";
import SigndInChecker from "./SigndInChecker";
import FullscreenToggle from "./Fullscreen";
import User from "./sideIcons/User"; 
import Sales from '../Pages/Sales'
import Orders from '../Pages/Orders'
import { Loader } from './custom_request';

export default function App() {
    return (
        <AuthProvider>
            <CartProvider>
                <ToastContainer/>
                <SigndInChecker/>
                <Loader/>
                <div className="container-fluid">
                    <Routes>
                        <Route path="/" element={<Sales />} />
                        <Route path="/orders" element={<Orders />} />
                    </Routes>
                    <User/>
                </div>
            </CartProvider>
        </AuthProvider>
    );
}