import { useState, useContext, useCallback } from 'react';
import { getData, Loader } from './custom_request';
import { successMessage, errorMessage } from './Alert';
import { CartContext } from "./context/CartContext";
import { AuthContext } from "./context/AuthContext";
import AddStock from "./Modal/AddStock"; 

export default function ProductContainer() {
    const { authToken, setPageLoading } = useContext(AuthContext);
    const { cart, addToCart, itemExists } = useContext(CartContext);
    const [products, setProducts] = useState(window.products || []);
    const [lastCategory, setLastCategory] = useState([]);
    const [page, setPage] = useState(1);
    const [hasMore, setHasMore] = useState(true);
    const [searching, setSearching] = useState(false); // New state for search loading

    function get_first_image(images) {
        if (images.length > 0) {
            return '/storage/' + images[0];
        }
        return "";
    }

    function open_category(category) {
        if (category.type == 1) {
            if (itemExists(category.id)) {
                return;
            }
            addToCart(category);
            return;
        }
        setLastCategory(_category => [..._category, category]);
        openCategory(category ? category.id : '');
    }

    function go_back() {
        let id = null;
        if (lastCategory.length > 0) {
            id = lastCategory[lastCategory.length - 1].parent_id;
            lastCategory.pop();
        }
        openCategory(id);
    }

    const openCategory = async (product_id = null) => {
        setPageLoading(true);
        const result = await getData('/api/get-data/' + window.shop_id + '/' + product_id + '/?page=' + page, authToken);
        if (result.data) {
            let data = result.data;
            setProducts(data);
        } else if (result.error) {
            errorMessage(`${result.error.message}`, `${result.error.title}`);
        }
        setPageLoading(false);
    };

    const fetchSearchResults = async (query) => {
        if (!query) {
            setProducts(window.products || []); // Reset to initial products if search query is empty
            setSearching(false); // Hide search loading indicator when no query
            return;
        }
        setSearching(true); // Show search loading indicator
        const result = await getData('/api/search-item/' + window.shop_id + '/' + query, authToken);
        if (result.data) {
            let data = result.data;
            setProducts(data);
        }
        setSearching(false); // Hide search loading indicator when search completes
    };

    // Debounce function
    const debounce = (func, delay) => {
        let timer;
        return (...args) => {
            clearTimeout(timer);
            timer = setTimeout(() => func(...args), delay);
        };
    };

    const debouncedSearch = useCallback(
        debounce((e) => {
            const query = e.target.value.trim();
            fetchSearchResults(query);
        }, 500), // Adjust the delay as needed
        []
    );

    return (
        <div className="product-container">
            <div className="search-bar">
                <input
                    type="text"
                    className="form-control search-input"
                    placeholder="Search for Products"
                    onInput={debouncedSearch}
                />
                {searching && (
                    <div className="search-loading-indicator">
                        <i className="fas fa-spinner fa-spin"></i> Searching...
                    </div>
                )}
            </div>
            {lastCategory.length > 0 ?
                <button onClick={() => go_back()} className='btn btn-primary m-2'>
                    <i className='fas fa-arrow-left'></i> Go back
                </button>
                : ''
            }

            <div className='product_list_con container-fluid'>
                <div className="row the_row">
                    {products.map((product, index) => (
                        <div key={index} className="col-md-3">
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
                                        <button onClick={()=>alert('yeah')} className='btn btn-primary btn-sm'>Stock</button>
                                        <div><br /></div>
                                    </>
                                    : <div style={{minHeight: '80px'}}></div>
                                }
                            </div>
                        </div>
                    ))}
                </div>

                {products.length == 0 ?
                    <div className='text-center'>No product in this category</div>
                    : ''
                }
            </div>
            
        </div>
    );
}
