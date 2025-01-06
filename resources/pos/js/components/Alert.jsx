import { toast } from "react-toastify";
import "react-toastify/dist/ReactToastify.css";

export const successMessage = (msg, title) => {
    toast.success(
        <>
            <strong>{title ?? "Success!"}</strong>
            <div>{msg}</div>
        </>,
        {
            position: 'top-center',
            icon: "✔️", // Custom icon
        }
    );
};

export const errorMessage = (msg, title) => {
    toast.error(
        <>
            <strong>{title ?? "Error!"}</strong>
            <div>{msg}</div>
        </>,
        {
            position: 'top-center',
            icon: "⚠️", // Custom icon
        }
    );
};
