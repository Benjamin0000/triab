

export default function AddStock({product, show, handleClose}) {
   
    return (
        <>
            <Modal show={show} onHide={handleClose} >
                <Modal.Header closeButton className="bg-dark text-light">
                    <Modal.Title>{product.name}</Modal.Title>
                </Modal.Header>
                <Modal.Body className="bg-dark text-light">


                </Modal.Body>
            </Modal>
        </>
    ); 

}