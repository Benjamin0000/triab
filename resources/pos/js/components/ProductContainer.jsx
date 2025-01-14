import { useState, useContext, useCallback } from 'react';
import { getData } from './custom_request';
import { successMessage, errorMessage } from './Alert';
import { CartContext } from "./context/CartContext";
import { AuthContext } from "./context/AuthContext";
import Product from './Product';
import AddProduct from '../components/Modal/AddProduct'

export default function ProductContainer() {
    const { authToken, setPageLoading } = useContext(AuthContext);
    const { addToCart, itemExists } = useContext(CartContext);
    const [products, setProducts] = useState(window.products || []);
    const [lastCategory, setLastCategory] = useState([]);
    const [page, setPage] = useState(1);
    const [hasMore, setHasMore] = useState(true);
    const [searching, setSearching] = useState(false); // New state for search loading
    const [showAddProductModal, setShowAddProductModal] = useState(false); 

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
            { lastCategory.length > 0 ?
                <button onClick={() => go_back()} className='btn btn-primary m-2'>
                    <i className='fas fa-arrow-left'></i> Go back
                </button>
                : ''
            }
            {/* <button className='btn btn-primary' onClick={()=>setShowAddProductModal(true)}>
                Add { lastCategory.length <= 0 ? 'category' : 'product' }
            </button> */}
            <div className='product_list_con container-fluid'>
{/*             
                <AddProduct 
                    show={showAddProductModal} 
                    handleClose={()=>setShowAddProductModal(false)} 
                    updateProducts={setProducts}
                    lastCategory={lastCategory}
                /> */}
                <div className="row the_row">
                    { products.map((product, index) => (
                        <Product 
                            key={index}
                            product={product} 
                            handleSetProducts={setProducts} 
                            open_category={open_category}
                        />
                    )) }
                </div>

                {products.length == 0 ?
                    <div className='text-center'>No product in this category</div>
                    : ''
                }
            </div>
            
        </div>
    );
}
