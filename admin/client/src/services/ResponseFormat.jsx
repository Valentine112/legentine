import './css/ResponseFormat.css'

const ResponseFormat = (data) => {
  data = data.data

  return (
    <div className='rMessage mx-auto'>
        <div>
            <header>
                <h4 className='h6'>{data.type.toUpperCase()}</h4>
            </header>
            <div style={{color: data.type == "success" ? "lemongreen" : "red",
                        fontSize: '13px'
                        }}>
                {data.content}
            </div>
        </div>
    </div>
  )
}

export default ResponseFormat