import './css/ResponseFormat.css'

const ResponseFormat = (data) => {
  data = data.data

  return (
    <div className='rMessage mx-auto'>
        <div>
            <header>
                <h4 className='h6'>{data.type === 1 ? "Error" : "Success"}</h4>
            </header>
            <div style={{color: data.type === 1 ? "red" : "lemongreen",
                        fontSize: '13px'
                        }}>
                {data.content}
            </div>
        </div>
    </div>
  )
}

export default ResponseFormat