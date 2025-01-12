import { useEffect, useState, useContext } from "react";
import { AuthContext } from "./context/AuthContext"; 
import { getData } from './custom_request'; 

export default function SignInChecker() {
    const { authToken, loading } = useContext(AuthContext);
    const [signdIn, setSigndIn] = useState(false);

    useEffect(() => {
        if (!loading) {
            if (!authToken) {
                // No auth token, redirect to login immediately
                console.warn("No auth token found. Redirecting to login...");
                window.location.href = '/pos-login';
            } else {
                check_authentication();
            }
        }
    }, [loading, authToken]);

    async function check_authentication() {
        try {
            const result = await getData(`/api/check-auth/${window.shop_id}`, authToken);
            console.log("Auth check response:", result.data);

            if (result.data && result.data.signdIn === true) {
                setSigndIn(true); // User is signed in
            } else {
                console.warn("Invalid auth token. Redirecting to login...");
                window.location.href = '/pos-login';
            }
        } catch (error) {
            console.error("Error during auth check:", error);
            window.location.href = '/pos-login'; // Redirect on failure
        }
    }

    return (
        <>
            {loading && (
                <div id="the-loader">
                    <div className="spinn"></div>
                </div>
            )}
            {!loading && !signdIn && (
                <div id="the-loader">
                    <div className="spinn"></div>
                </div>
            )}
        </>
    );
}
