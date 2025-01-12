import { useState, useContext, useEffect} from "react";
import { CartContext } from "../context/CartContext"; 
import { AuthContext } from "../context/AuthContext"; 
import { Modal, Button } from "react-bootstrap";
import { Link } from "react-router-dom";

export default function UserIcon(){
    const [show, setShow] = useState(false);
    const handleShow = ()=>setShow(true); 
    const handleClose = ()=>setShow(false);
    const {emptyCart} = useContext(CartContext); 
    const {setAuthToken, authToken} = useContext(AuthContext);
    const [user, setUser] = useState(JSON.parse(localStorage.getItem('admin'))); 

    const handleLogout = ()=>{
        emptyCart(); 
        setAuthToken('')
        window.location.reload(); 
    }

    return (
        <>
            <Modal className="modal-sm" show={show} onHide={handleClose}>
                <Modal.Header closeButton className="bg-dark text-light">
                    <Modal.Title>User</Modal.Title>
                </Modal.Header>
                <Modal.Body className="bg-dark text-center">
                    <table className="table">
                        <tr>
                            <td>Staff</td>
                            <td>{user.name}</td>
                        </tr>
                        <tr>
                            <td>Role</td>
                            <td>{ user.admin ? 'Admin' : 'Sales Rep.'  }</td>
                        </tr>
                    </table>
                    <Button onClick={handleLogout} variant="primary">
                            Logout
                    </Button> 
                </Modal.Body>
            </Modal>

            <button onClick={handleShow} className="auth_btn btn btn-secondary"><i className="fas fa-user"></i></button>
            <Link to='/orders' className="record_btn btn btn-secondary"><i className="fas fa-book"></i></Link>
           
            <Link to='/' className="cart_btn btn btn-secondary"> 
                <i className="fa fa-shopping-cart" style={{fontSize: '13px'}}></i>
            </Link>
        </>
    ); 
}