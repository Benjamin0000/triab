import React, { createContext, useState, useEffect } from 'react';

export const CartContext = createContext();

export const CartProvider = ({ children }) => {
  const [carts, setCarts] = useState([]);
  const [total_cost, setTotal] = useState(0);

  const calculateTotal = () => {
    let cost = 0;
    carts.forEach((item) => {
      cost += item.price * item.qty;
    });
    setTotal(cost);
  };

  // Load cart from localStorage
  useEffect(() => {
    const loadCarts = () => {
      const savedCarts = localStorage.getItem('carts');
      if (savedCarts) {
        setCarts(JSON.parse(savedCarts));
      }
    };
    loadCarts();
  }, []);

  // Save carts to localStorage when they change
  useEffect(() => {
    localStorage.setItem('carts', JSON.stringify(carts));
    calculateTotal();
  }, [carts]);

  const addToCart = (Item) => {
    const currentCart = carts || [];
    const itemIndex = currentCart.findIndex((item) => item.id === Item.id);

    let updatedCart;

    if (itemIndex !== -1) {
      updatedCart = currentCart.map((item, index) =>
        index === itemIndex ? { ...item, qty: item.qty + 1 } : item
      );
    } else {
      updatedCart = [
        ...currentCart,
        {
          id: Item.id,
          name: Item.name,
          qty: 1,
          price: Item.selling_price,
          logo: Item.logo,
        },
      ];
    }
    setCarts(updatedCart);
  };

  const removeFromCart = (itemId) => {
    const currentCart = carts || [];
    const itemIndex = currentCart.findIndex((item) => item.id === itemId);
  
    if (itemIndex !== -1) {
      const updatedCart = currentCart.map((item, index) =>
        index === itemIndex
          ? { ...item, qty: Math.max(1, item.qty - 1) } // Ensure qty does not go below 1
          : item
      );
      setCarts(updatedCart);
    }
  };
  

  const deleteFromCart = (itemId) => {
    setCarts((prevCarts) => prevCarts.filter((item) => item.id !== itemId));
  };

  const emptyCart = () => {
    setCarts([]);
  };

  // New setQty method
  const putQty = (itemId, qty) => {
    if (qty < 1) return; // Ensure qty is at least 1
    const updatedCart = carts.map((item) =>
      item.id === itemId ? { ...item, qty } : item
    );
    setCarts(updatedCart);
  };

  const getItem = (itemId) => {
    const currentCart = carts || [];
    const itemIndex = currentCart.findIndex(item => item.id === itemId);
    if (itemIndex !== -1) {
      return currentCart[itemIndex]; 
    }
    return {}; 
  };

  const itemExists = (itemId) => {
    const currentCart = carts || [];
    const itemIndex = currentCart.findIndex(item => item.id === itemId);
    if (itemIndex !== -1) {
      return true; 
    }
    return false;  
  };


  return (
    <CartContext.Provider
      value={{
        cart: carts,
        total_cost,
        addToCart,
        removeFromCart,
        deleteFromCart,
        emptyCart,
        putQty, 
        itemExists
      }}
    >
      {children}
    </CartContext.Provider>
  );
};
