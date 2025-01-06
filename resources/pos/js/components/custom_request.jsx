import axios from "axios";

export const getData = async (url , token="") => {

    try {
        const response = await axios.get(url, {
            timeout: 10000,  // Set a timeout to avoid indefinite waiting for a response
            headers: {
            'Content-Type': 'application/json',
            ...(token ? { Authorization: `Bearer ${token}` } : {})
            }
        });


    if(response.data.error){
        return {
            error: {
                title: 'Error',
                message: response.data.error
            }
        }
    }

    return {
        data:response.data 
    } 

    } catch (error) {
        let title = "";
        let message = ""; 
        if (!error.response) {
        // No response from the server - either no internet or server is unreachable
        if (error.message.includes('Network Error')) {
            // Likely due to no internet connection
            title = 'Connection Error'
            message = 'It seems there is no internet connection.'
        } else if (error.code === 'ECONNABORTED') {
            // Request timed out (server did not respond in time)
            title = 'Connection Error'
            message = 'The server took too long to respond, please check if you have a stable internet connection'
        } else {
            title = 'Unknown Error'
            message = 'Something didn\'t go right'
        }
        } else {
            // The server responded but with an error status code
            const statusCode = error.response.status;
            if (statusCode === 500) {
                title = 'Server Error'
                message = 'There was a problem with the server.'
            } else {
                title = 'Request Error'
                message = `Error: ${statusCode}`
            }
        }
        return {
            error:{
                title:title,
                message:message 
            }
        }
    }
};

export const postData = async (url, payload, token) => {
    try {
      const response = await axios.post(url, payload, {
        timeout: 10000,  // Set a timeout to avoid indefinite waiting for a response
        headers: {
          'Content-Type': 'application/json',
          ...(token ? { Authorization: `Bearer ${token}` } : {})
        }
      });
  
      if(response.data.error){
        return {
          error: {
            title: 'Error',
            message: response.data.error
          }
        }
      }
      
      return {
        data: response.data
      };
  
    } catch (error) {
      let title = "";
      let message = ""; 
  
      
      console.log(JSON.stringify(error.response, null, 4));
  
      if (!error.response) {
        // No response from the server - either no internet or server is unreachable
        if (error.message.includes('Network Error')) {
          title = 'Connection Error';
          message = 'It seems there is no internet connection.';
        } else if (error.code === 'ECONNABORTED') {
          // Request timed out (server did not respond in time)
          title = 'Connection Error';
          message = 'The server took too long to respond, please check if you have a stable internet connection';
        } else {
          title = 'Unknown Error';
          message = 'Something didn\'t go right';
        }
      } else {
        // The server responded but with an error status code
        const statusCode = error.response.status;
        if (statusCode === 500) {
          title = 'Server Error';
          message = 'There was a problem with the server.';
        } else {
          title = 'Request Error';
          message = `Error: ${statusCode}`;
        }
      }
  
      return {
        error: {
          title: title,
          message: message
        }
      };
    }
};



export const Loader = ({ position = "right" }) => {
    return (
        <div className={`loader-container ${position}`}>
            <div className="spinner"></div>
        </div>
    );
};