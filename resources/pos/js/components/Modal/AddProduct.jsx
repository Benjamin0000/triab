import React, { useState } from "react";
import { Modal, Button, Form } from "react-bootstrap";
import { postData } from '../custom_request';
import { AuthContext } from "../context/AuthContext";


export default function AddProduct({ show, handleClose, lastCategory, updateProducts  }) {
    
  const [product, setProduct] = useState({
    name: "",
    sellingPrice: "",
    costPrice: "",
    isCategory: false,
  });

  const handleInputChange = (e) => {
    const { name, value, type, checked } = e.target;
    setProduct({
      ...product,
      [name]: type === "checkbox" ? checked : value,
    });
  };

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

  function getLastCategory(){
    return lastCategory[lastCategory.length - 1]; 
  }

  return (
    <>
      <Modal show={show} onHide={handleClose}>
        <Modal.Header closeButton className="bg-dark text-light">
          <Modal.Title>Add A New { lastCategory.length <= 0 || product.isCategory ? 'Category' : 'Product' } </Modal.Title>
        </Modal.Header>
        <Modal.Body className="bg-dark text-light">
          <div className="text-center">
            { lastCategory.length > 0 ? 'Category: ' + getLastCategory().name  : '' }
          </div>
          <Form onSubmit={handleFormSubmit}>
            <Form.Group className="mb-3">
              <Form.Label>
              { lastCategory.length <= 0 || product.isCategory ? 'Category' : 'Product' } Name
             </Form.Label>
              <Form.Control
                type="text"
                placeholder={'Enter a '+ (lastCategory.length <= 0 || product.isCategory ? 'category' : 'product') +" name"}
                name="name"
                value={product.name}
                onChange={handleInputChange}
                required
              />
            </Form.Group>
            { lastCategory.length > 0 && (
                <>
                    <Form.Group className="mb-3">
                        <Form.Check
                            type="checkbox"
                            label="Is this a category?"
                            name="isCategory"
                            checked={product.isCategory}
                            onChange={handleInputChange}
                        />
                    </Form.Group>
                    {!product.isCategory && (
                        <>
                            <Form.Group className="mb-3">
                            <Form.Label>Selling Price</Form.Label>
                            <Form.Control
                                type="number"
                                placeholder="Enter selling price"
                                name="sellingPrice"
                                value={product.sellingPrice}
                                onChange={handleInputChange}
                                required
                            />
                            </Form.Group>

                            <Form.Group className="mb-3">
                            <Form.Label>Cost Price</Form.Label>
                            <Form.Control
                                type="number"
                                placeholder="Enter cost price"
                                name="costPrice"
                                value={product.costPrice}
                                onChange={handleInputChange}
                                required
                            />
                            </Form.Group>
                        </>
                    )}
                </>
            )}

            <Button variant="primary" type="submit">
                Add { lastCategory.length <= 0 || product.isCategory ? 'category' : 'product' }
            </Button>
          </Form>
        </Modal.Body>
      </Modal>
    </>
  );
}
