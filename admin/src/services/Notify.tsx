import "./css/notify.css"

const Notify = () => {
  return (
    <div className="notify col-11 mx-auto">
        <div className="mx-auto">
            <header>
                <span className="notiStatus success">Success</span>
            </header>
            <div>
                <span className="notiMessage">Successfully logged in</span>
            </div>
        </div>
    </div>
  )
}

export default Notify