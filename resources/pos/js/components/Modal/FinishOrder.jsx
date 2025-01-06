import { useState, useContext, useEffect, useRef } from "react";
import { Modal, Button } from "react-bootstrap";
import {AuthContext} from "../context/AuthContext"; 
import {CartContext} from "../context/CartContext"; 
import { postData } from '../custom_request'; 
import {successMessage, errorMessage} from '../Alert';

export default function FinishOrder({show, handleClose, total_cost}) {
    const {authToken} = useContext(AuthContext);
    const {cart, emptyCart} = useContext(CartContext);
    const [receiptHtml, setReceiptHtml] = useState('');
    const [canPrint, setCanPrint] = useState(false);
     
    const [vat, setVat] = useState(Number(window.vat)); // %
    const [fee, setFee] = useState(Number(window.service_fee)); // Fixed value
    const [payMethod, setPayMethod] = useState('');
    const [loading, setLoading] = useState(false); 
    

    const paymentType = ["Cash", "POS", "Bank Transfer", "Debt"];

    function calculate_total_cost()
    {
        return ( (vat/100)*total_cost + total_cost) + fee; 
    }

    async function submit_order()
    {
        if(cart.length <= 0){
                return ;
        }
        if(!payMethod){
            errorMessage("", "Select a payment method"); 
            return 
        }
        let data = {
            shop_id:window.shop_id, 
            cart:cart, 
            pay_method:payMethod
        }

        setLoading(true)
        let response = await postData('/api/save-order', data, authToken); 
        if (response.data) {
            let data = response.data; 
            if(data.receipt){
                emptyCart();
                setReceiptHtml(data.receipt);
                successMessage("Order completed", "Completed"); 
                setPayMethod(''); 
                setCanPrint(true)
            }
        }else if(response.error){
            let error = response.error; 
            errorMessage(error.message, error.title); 
        }
        setLoading(false)
    }

    const handlePrint = (html) => {
        const printWindow = window.open("", "", "width=800,height=600");
        printWindow.document.write(`
            <html>
            <head>
                <title>Receipt</title>
                <style>
                    @media print {
                        body { margin: 0; }
                        .no-print { display: none; }
                    }
                </style>
            </head>
            <body>
                ${html}
            </body>
            </html>
        `);
        printWindow.document.close();
        printWindow.focus();
        printWindow.print();
        printWindow.close();
    };

    useEffect(()=>{
        if(cart.length > 0){
            setCanPrint(false); 
        }
    }, [cart])

    return (
        <>
            <Modal show={show} onHide={handleClose} >
                <Modal.Header closeButton className="bg-dark text-light">
                    <Modal.Title>Finish Order</Modal.Title>
                </Modal.Header>
                <Modal.Body className="bg-dark text-light">
                <table className="table table-bordered">
                    <tbody>
                        <tr>
                            <th>Subtotal</th>
                            <th>₦{total_cost.toLocaleString()}</th>
                        </tr>
                        <tr>
                            <th>VAT</th>
                            <th>{vat}%</th>
                        </tr>
                        <tr>
                            <th>Service Charge</th>
                            <th>₦{fee}</th>
                        </tr>
                        <tr>
                            <th>Total Cost</th>
                            <th>₦{calculate_total_cost().toLocaleString()}</th>
                        </tr>
                    </tbody>
                </table>
                <div>
                    <select name="pay_method" onChange={(e)=>setPayMethod(e.target.value)} className="form-select p-3 my-1">
                        <option>Select Payment Method</option>
                        {paymentType.map((method, key) => (
                            <option selected={method == payMethod} key={key} value={method}>
                                {method}
                            </option>
                        ))}
                    </select>
                </div>
                </Modal.Body>
                <Modal.Footer className="bg-dark text-light">
                    { canPrint ? 
                        <Button  onClick={()=>handlePrint(receiptHtml)} variant="primary">
                            Print Receipt
                        </Button> : ''
                    }

                    { !canPrint ? 
                        <Button disabled={loading} onClick={()=>submit_order()} variant="primary">
                            { loading? 'Processing...' : 'Finish Order' }
                        </Button> : ''
                    }
                </Modal.Footer>
            </Modal>
        </>
    ); 
}