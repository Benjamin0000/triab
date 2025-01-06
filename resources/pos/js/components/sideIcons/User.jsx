import { useState, useContext, useEffect} from "react";
import { CartContext } from "../context/CartContext"; 
import { Modal, Button } from "react-bootstrap";

export default function UserIcon(){
    const [show, setShow] = useState(false);
    const handleShow = ()=>setShow(true); 
    const handleClose = ()=>setShow(false);
    const handleLogout = ()=>{

    }
    return (
        <>
            <Modal className="modal-sm" show={show} onHide={handleClose}>
                <Modal.Header closeButton className="bg-dark text-light">
                    <Modal.Title>User</Modal.Title>
                </Modal.Header>
                <Modal.Body className="bg-dark text-center">
                    <Button onClick={handleLogout} variant="primary">
                            Logout
                    </Button> 
                </Modal.Body>
            </Modal>

            <button onClick={handleShow} className="auth_btn btn btn-secondary"><i className="fas fa-user"></i></button>
        </>
    ); 
}