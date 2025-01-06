import { useState, useContext, useEffect } from "react";
import {CartContext} from "./context/CartContext";


const Item = ({ item, index }) => {
    const {cart, addToCart, removeFromCart, deleteFromCart,  putQty} = useContext(CartContext);
    const [qty, setQty] = useState(item.qty);

    const handleQtyChange = (e, index) => {
        let new_qty = Number(e.target.value); 
        if(new_qty <= 0){
            setQty(''); 
        }else{
            setQty(new_qty);
        }
        if(new_qty > 0)
            putQty(item.id, new_qty); 
    }

    const handleKeyDown = (event) => {
        if (event.key === 'e' || event.key === 'E') {
          event.preventDefault();
        }
    };

    useEffect(()=>{
        setQty(item.qty);
    }, [cart])

    return (
        <div className="cart_item_con">
            <div className="rowed">
                <div className="rowed_one">
                    <strong>{item.name}</strong>
                </div>
                <div className="text-info" style={{fontSize:'14px'}}>
                    ₦{Number(item.price).toLocaleString()}
                </div>
            </div>
            <div style={{ fontSize: "20px", fontWeight: "bold", marginTop:'4px', textAlign:'center' }}>
                ₦{(item.qty * item.price).toLocaleString()}
            </div>
            <div className="rowed_two">
                    <button
                        className="btn btn-sm btn-outline-light"
                        onClick={() => removeFromCart(item.id)}
                    >
                        <i className="fas fa-minus"></i>
                    </button>
                    <input
                        type="number"
                        value={qty}
                        onKeyDown={handleKeyDown}
                        onChange={(e) => handleQtyChange(e, index)}
                        className="quantity-input"
                        onBlur={() =>setQty(item.qty)}
                    />
                    <button
                        className="btn btn-sm btn-outline-light"
                        onClick={() => addToCart(item)}
                    >
                        <i className="fas fa-plus"></i>
                    </button>
            </div>
            <div className="rowed_three text-center">
                <button
                    className="btn btn-sm btn-danger"
                    onClick={() => deleteFromCart(item.id)}
                >
                    <i className="fa fa-trash"></i> Remove
                </button>
            </div>
        </div>
    );
};

export default Item;