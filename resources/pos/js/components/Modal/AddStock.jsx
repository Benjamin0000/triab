import { useState, useContext } from "react";
import { Modal } from "react-bootstrap";
import { postData } from '../custom_request';
import { AuthContext } from "../context/AuthContext";
import { successMessage, errorMessage } from '../Alert';

export default function AddStock({ product, show, handleClose, updateProducts }) {
  const REMOVE_STOCK = 0;
  const ADD_STOCK = 1;

  const { authToken, setPageLoading } = useContext(AuthContext);
  const [type, setType] = useState(ADD_STOCK);
  const [qty, setQty] = useState(0);
  const [reason, setReason] = useState("");
  const [loading, setLoading] = useState(false); 


  const reasonsForAddStock = ["New Stock", "Stock Increase"];
  const reasonsForRemoveStock = ["Damage", "For Use", "Excess"];

  const handleTypeSwitch = (e) => {
    const selectedType = parseInt(e.target.value, 10);
    setType(selectedType);
    setReason(""); // Reset reason when switching type
  };


  const updateItemTotal = (productId, newTotal) =>{
    updateProducts((prevProducts) =>
      prevProducts.map((product) =>
        product.id === productId
          ? { ...product, total: newTotal } // Update the total for the matching product
          : product // Keep other products unchanged
      )
    );
    setQty(0);
  }

  const handleFormSubmit = async (e) => {
    e.preventDefault();
    if(qty <= 0) return; 
    let data = {
        type: type === ADD_STOCK ? "1" : "0",
        qty: qty,
        reason: reason,
        product_id: product.id
    };

    setPageLoading(true); 
    setLoading(true); 
    let response = await postData('/api/stocking', data, authToken); 
    if (response.data) {
        let data = response.data; 
        if(data.total){
            successMessage("", "Stock updated");
            updateItemTotal(product.id, data.total);
            handleClose();
        }
    }else if(response.error){
        let error = response.error; 
        errorMessage(error.message, error.title); 
    }
    setLoading(false)
    setPageLoading(false);
  };

  return (
    <>
      <Modal show={show} onHide={handleClose}>
        <Modal.Header closeButton className="bg-dark text-light">
          <Modal.Title>Stock for "{product.name}"</Modal.Title>
        </Modal.Header>
        <Modal.Body className="bg-dark text-light">
          <form onSubmit={handleFormSubmit}>
            <div className="form-group mb-3">
              <label htmlFor="type" className="form-label">Action</label>
              <select
                id="type"
                value={type}
                onChange={handleTypeSwitch}
                className="form-select"
              >
                <option value={ADD_STOCK}>Add Stock</option>
                <option value={REMOVE_STOCK}>Remove Stock</option>
              </select>
            </div>

            <div className="form-group mb-3">
              <label htmlFor="qty" className="form-label">Quantity</label>
              <input
                type="number"
                id="qty"
                name="qty"
                value={qty}
                onChange={(e) => setQty(e.target.value)}
                className="form-control"
                required
              />
            </div>

            <div className="form-group mb-3">
              <label htmlFor="reason" className="form-label">Reason</label>
              <select
                id="reason"
                value={reason}
                onChange={(e) => setReason(e.target.value)}
                className="form-select"
                required
              >
                <option value="">Select Reason</option>
                {(type === ADD_STOCK ? reasonsForAddStock : reasonsForRemoveStock).map(
                  (reason, index) => (
                    <option key={index} value={reason}>
                      {reason}
                    </option>
                  )
                )}
              </select>
            </div>

            <div className="form-group">
              <button disabled={loading} type="submit" className={`btn btn-${type === ADD_STOCK ? "primary" : "danger"} w-100`}>
                {
                    loading ? 'Sending...' : 
                    type === ADD_STOCK ? "Add Stock" : "Remove Stock"
                }
              </button>
            </div>
          </form>
        </Modal.Body>
      </Modal>
    </>
  );
}
