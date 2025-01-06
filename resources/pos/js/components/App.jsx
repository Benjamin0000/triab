import SalesContainer from "./SalesContainer";
import ProductContainer from "./ProductContainer";
import { CartProvider } from "./context/CartContext";
import { AuthProvider } from "./context/AuthContext"; 
import { ToastContainer } from "react-toastify";
import SigndInChecker from "./SigndInChecker";
import FullscreenToggle from "./Fullscreen";

export default function App() {
    return (
        <AuthProvider>
            <CartProvider>
                <ToastContainer/>
                <SigndInChecker/>
                <div className="container-fluid">
                    <div className="row">
                        <div className="col-3 p-0">
                            <SalesContainer/>
                        </div>
                        <div className="col-9 p-0">
                            
                            <ProductContainer/>
                        </div>
                    </div>
                </div>
            </CartProvider>
        </AuthProvider>
    );
}