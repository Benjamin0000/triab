import { useEffect, useState, useContext } from "react";
import { Link } from "react-router-dom";
import { AuthContext } from "../components/context/AuthContext";
import { getData, handlePrint } from "../components/custom_request";
import $ from 'jquery'; 

export default function Orders() {
    const { authToken, setPageLoading } = useContext(AuthContext);
    const [orders, setOrders] = useState([]);
    const [page, setPage] = useState(1);
    const [loading, setLoading] = useState(false);
    const [hasMore, setHasMore] = useState(true);
    const [receiptHtml, setReceiptHtml] = useState("");
    const [searchId, setSearchId] = useState("");

    // Fetch orders from the server
    const getOrders = async (append = true) => {
        const expectedLength = 20;
        setLoading(true);
        const result = await getData(`/api/get-orders/${window.shop_id}/?page=${page}`, authToken);

        if (result.data) {
            const data = result.data.orders;
            setOrders((prev) => (append ? [...prev, ...data] : data));
            setHasMore(data.length >= expectedLength);
        } else if (result.error) {
            errorMessage(result.error.message, result.error.title);
        }

        setLoading(false);
        setPageLoading(false);
    };

    // Fetch receipt by order ID
    const getReceipt = async (id) => {
        setPageLoading(true);
        const result = await getData(`/api/get-receipt/${id}`, authToken);

        if (result.data) {
            setReceiptHtml(result.data.receipt);
            $("#receipt-frame").css('background', 'white')
        } else if (result.error) {
            errorMessage(result.error.message, result.error.title);
        }

        setPageLoading(false);
    };

    // Search for a specific order
    const searchOrder = async () => {
        if (!searchId) return;
        setPageLoading(true);
        const result = await getData(`/api/get-order/${searchId}`, authToken);
        if (result.data) {
            setOrders(result.data.order);
            setHasMore(false);
        } else if (result.error) {
            errorMessage(result.error.message, result.error.title);
        }

        setPageLoading(false);
    };

    // Load more orders on scroll
    const handleScroll = (e) => {
        const { scrollTop, scrollHeight, clientHeight } = e.target;
        if (scrollTop + clientHeight >= scrollHeight - 10 && hasMore && !loading) {
            setPage((prev) => prev + 1);
        }
    };

    const formattedDate = (date)=>{
        return new Date(date).toLocaleString("en-US", {
            year: "numeric",
            month: "short",
            day: "numeric",
            hour: "numeric",
            minute: "numeric",
            second: "numeric",
            hour12: true, // 12-hour clock
        });
    }
    

    useEffect(() => {
        if(authToken){
            getOrders();
        }
    }, [page, authToken]);


    return (
        <>
        <div style={{paddingTop:'20px'}}>
            <div className="row">
                <div className="col-md-8">
                    <h3 className="text-center">Sales History</h3>
                    <div className="mb-3">
                        <input
                            type="text"
                            className="form-control"
                            placeholder="Search Order ID"
                            value={searchId}
                            onChange={(e) => setSearchId(e.target.value)}
                            onKeyDown={(e) => e.key === "Enter" && searchOrder()}
                        />
                        <button className="btn btn-primary mt-2" onClick={searchOrder}>
                            Search
                        </button>
                    </div>
                    <div
                        id="scroll_container"
                        className="table-responsive"
                        style={{ maxHeight: "70vh", overflowY: "auto" }}
                        onScroll={handleScroll}
                    >
                        <table className="table table-striped table-hover">
                            <thead className="table-primary" style={{position: 'sticky', top: 0, zIndex: 1}}>
                                <tr>
                                    <th>No</th>
                                    <th>Order ID</th>
                                    <th>Date</th>
                                    <th>Staff</th>
                                    <th>Total</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                {orders.map((order, index) => (
                                    <tr key={index}>
                                        <td>{order.no}</td>
                                        <td>{order.orderID}</td>
                                        <td>{formattedDate(order.created_at)}</td>
                                        <td>{order.staff}</td>
                                        <td>₦{Number(order.total).toLocaleString()}</td>
                                        <td>
                                            <button
                                                onClick={() => getReceipt(order.id)}
                                                className="btn btn-sm btn-primary"
                                            >
                                                View
                                            </button>
                                        </td>
                                    </tr>
                                ))}
                            </tbody>
                        </table>
                        {loading && <h6 className="text-center mt-2">Loading more...</h6>}

                        { orders.length == 0 ?
                            <div className='text-center'>No orders to show</div>
                            : ''
                        }
                    </div>
                </div>
                <div className="col-md-4">
                    
                {receiptHtml && ( 
                    <h6>Receipt</h6>
                )}
                    <iframe
                        id="receipt-frame"
                        style={{ width: "80mm", height: "100%", border: "none" }}
                        srcDoc={receiptHtml}
                        title="Receipt"
                    ></iframe>
                    <div>
                        {receiptHtml && (
                            <button
                                className="btn btn-primary"
                                onClick={() => handlePrint(receiptHtml)}
                            >
                                Print Receipt
                            </button>
                        )}
                    </div>
                </div>
            </div>
        </div>
        </>
    );
}
