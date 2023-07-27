import React, { useEffect, useRef } from 'react'
import "../css/access/login.css";
import useAuth from "../../hooks/useAuth";
import ResponseFormat from "../../services/ResponseFormat";

function Login() {
  const auth = useAuth()

  // Hiding the error box after 3secs
  useEffect(() => {
    setTimeout(() => {auth.clientError && auth.setClientError(false)}, 3000)
  }, [auth.clientError])
  

  return (
    <div>
      <div className="container">
        <header className="mx-auto col-12 text-center mt-5">
          <h1 className="text-center">Manager</h1>
        </header>

        <main>
            <form action="post" onSubmit={(e) => (
                e.preventDefault(),
                auth.login()
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
                        value="Kakarotgoku"
                        ref={auth.username}
                    />
                    </div>
                    <div className="mt-3">
                    <input
                        type="password"
                        className="form-control"
                        name="password"
                        data-auth="password"
                        placeholder="Password"
                        value="justapassword1!"
                        ref={auth.password}
                    />
                    </div>

                    <div className="mt-5 text-center">
                        <button type="submit" className="btn btn-primary form-control">Check?</button>
                    </div>
                </div>
            </form>
        </main>
      </div>

      {
        auth.clientError ? <ResponseFormat data={auth.message} /> : ""
      }

    </div>
  );
}

export default Login;
