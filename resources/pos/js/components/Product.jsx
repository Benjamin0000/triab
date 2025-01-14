import { useContext, useState } from 'react';
import AddStock from "./Modal/AddStock";
import { CartContext } from "./context/CartContext";

export default function Product({product, handleSetProducts, open_category}) {
    const { cart } = useContext(CartContext);
    const [showModal, setShowModal] = useState(false);
    const [user, setUser] = useState(JSON.parse(localStorage.getItem('admin'))); 

    return (
        <div className="col-md-3">
            <AddStock 
                product={product} 
                show={showModal}
                handleClose={()=>setShowModal(false)}
                updateProducts={handleSetProducts}
            />

            <div className="product-card">
                <div className="product-details" onClick={() => open_category(product)}>
                    <h6 className="product-name">{product.name}</h6>
                    <p className="product-price">
                        {product.type ?
                            '₦' + Number(product.selling_price).toLocaleString()
                            : 'Category'}
                    </p>
                </div>

                {cart.some(item => item.id === product.id) && (
                    <div className="check-icon"><i className='fas fa-check-circle'></i></div>
                )}

                { product.type ? 
                    <>
                        <h6>{product.total}</h6>

                        { user.admin ?
                            <>
                                <button onClick={()=>setShowModal(true)} className='btn btn-primary btn-sm'>Stock</button>
                                <div><br /></div>
                            </> : ''
                        }
                    </>
                    : <div style={{minHeight: '80px'}}></div>
                }
            </div>
        </div>
    )
}