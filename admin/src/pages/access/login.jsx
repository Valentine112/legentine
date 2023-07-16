import "../css/access/login.css";
import FormAuth from "../../services/FormAuth";

function Login() {

  return (
    <div>
      <div className="container">
        <header className="mx-auto col-12 text-center mt-5">
          <h1 className="text-center">Manager</h1>
        </header>
        <main>
            <form action="post" onSubmit={(e) => (
                    e.preventDefault()
                )
            }>
                <div className="col-11 col-md-8 col-lg-5 mx-auto mt-5">
                    <div className="mt-3">
                    <input
                        type="text"
                        className="form-control"
                        name="username"
                        data-auth="username"
                        placeholder="Username"
                    />
                    </div>
                    <div className="mt-3">
                    <input
                        type="text"
                        className="form-control"
                        name="password"
                        data-auth="password"
                        placeholder="Password"
                    />
                    </div>

                    <div className="mt-5 text-center">
                        <button type="submit" className="btn btn-primary form-control">Check?</button>
                    </div>
                </div>
            </form>
        </main>
      </div>
    </div>
  );
}

export default Login;
