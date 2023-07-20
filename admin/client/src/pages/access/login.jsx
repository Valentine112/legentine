import React, { useState, useEffect } from 'react'
import "../css/access/login.css";
import useAuth from "../../hooks/useAuth";
import ResponseFormat from "../../services/ResponseFormat";

function Login() {
  const Auth = useAuth()

  // Hiding the error box after 3secs
  useEffect(() => {
    setTimeout(() => {Auth.clientError && Auth.setClientError(false)}, 3000)
  }, [Auth.clientError])
  

  return (
    <div>
      <div className="container">
        <header className="mx-auto col-12 text-center mt-5">
          <h1 className="text-center">Manager</h1>
        </header>

        <main>
            <form action="post" onSubmit={(e) => (
                e.preventDefault(),
                Auth.login()
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
                        onChange={e => Auth.setUsername(e.target.value)}
                    />
                    </div>
                    <div className="mt-3">
                    <input
                        type="text"
                        className="form-control"
                        name="password"
                        data-auth="password"
                        placeholder="Password"
                        onChange={e => Auth.setPassword(e.target.value)}
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
        Auth.clientError ? <ResponseFormat data={Auth.message} /> : ""
      }

    </div>
  );
}

export default Login;
