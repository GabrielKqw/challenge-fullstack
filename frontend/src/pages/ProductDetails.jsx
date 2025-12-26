import { useState, useEffect } from 'react'
import { useParams, useNavigate } from 'react-router-dom'
import api from '../services/api'
import './ProductDetails.css'

function ProductDetails() {
  const { id } = useParams()
  const navigate = useNavigate()
  const [product, setProduct] = useState(null)
  const [loading, setLoading] = useState(true)
  const [error, setError] = useState('')

  useEffect(() => {
    loadProduct()
  }, [id])

  const loadProduct = async () => {
    try {
      setLoading(true)
      const response = await api.get(`/products/${id}`)
      setProduct(response.data)
      setError('')
    } catch (err) {
      setError('Product not found')
      console.error(err)
    } finally {
      setLoading(false)
    }
  }

  if (loading) {
    return <div className="loading">Loading product details...</div>
  }

  if (error || !product) {
    return (
      <div className="error-container">
        <div className="error">{error || 'Product not found'}</div>
        <button onClick={() => navigate('/')} className="btn-back">
          Back to Products
        </button>
      </div>
    )
  }

  return (
    <div className="product-details">
      <div className="details-header">
        <button onClick={() => navigate('/')} className="btn-back">
          ← Back to Products
        </button>
        <div className="header-actions">
          <button
            onClick={() => navigate(`/products/${id}/edit`)}
            className="btn-edit"
          >
            Edit Product
          </button>
        </div>
      </div>

      <div className="details-card">
        <h1>{product.name}</h1>
        
        <div className="details-grid">
          <div className="detail-item">
            <label>ID</label>
            <span>{product.id}</span>
          </div>
          
          <div className="detail-item">
            <label>Name</label>
            <span>{product.name}</span>
          </div>
          
          <div className="detail-item">
            <label>Description</label>
            <span>{product.description || 'No description'}</span>
          </div>
          
          <div className="detail-item">
            <label>Price</label>
            <span className="price">${product.price.toFixed(2)}</span>
          </div>
          
          <div className="detail-item">
            <label>Quantity</label>
            <span>{product.quantity}</span>
          </div>
          
          <div className="detail-item">
            <label>Status</label>
            <span className={`status ${product.active ? 'active' : 'inactive'}`}>
              {product.active ? 'Active' : 'Inactive'}
            </span>
          </div>
        </div>
      </div>
    </div>
  )
}

export default ProductDetails

