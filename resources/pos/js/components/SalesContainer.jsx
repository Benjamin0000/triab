import { useState, useEffect, useContext } from "react";
import FinishOrder from "./Modal/FinishOrder"; 
import {CartContext} from "./context/CartContext";
import Item from './Item';


export default function SalesContainer() {
    const { cart, total_cost, emptyCart } = useContext(CartContext);
    const [ show, setShow ] = useState(false);
    const handleClose = () => setShow(false);
    const handleShow = () =>{
        if(cart.length > 0){
            setShow(true)
        }
    } 
    return (
        <div className="sales_container">
            <div className="cart_item_container custom-scroll">
                {cart.map((item, index) => (
                    <Item key={index} item={item} index={index} />
                ))}
                { cart.length <= 0 ? <div className="text-center">Nothing in cart</div> : '' }
            </div>

            <div>
                <table className="table table-bordered">
                    <tbody>
                        <tr>
                            <th>Subtotal</th>
                            <th>₦{total_cost.toLocaleString()}</th>
                        </tr>
                    </tbody>
                </table>
                <FinishOrder total_cost={total_cost} show={show} handleClose={handleClose}/>
                <div className="row">
                    <div className="col-md-6">
                        <button onClick={()=>emptyCart()} className="btn btn-danger w-100">Clear</button>
                    </div>
                    <div className="col-md-6">
                        <button onClick={()=>handleShow()} className="btn btn-primary w-100">Complete Order</button>
                    </div>
                </div>
            </div>
        </div>
    );
}
