import SalesContainer from '../components/SalesContainer'
import ProductContainer from '../components/ProductContainer'

export default function Sales() {

    return (
        <div className="row">
            <div className="col-3 p-0">
                <SalesContainer/>
            </div>
            <div className="col-9 p-0">
                <ProductContainer/>
            </div>
        </div>
    )
}