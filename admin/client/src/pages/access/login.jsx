import React, { useEffect } from 'react'
import "../css/access/login.css";
import "../../output.css"
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
      <div className="w-fit bg-white fixed bottom-0 top-0 right-0 left-0 border-4  border-dotted border-red mx-auto">
        <header className="w-full bg-red">
          <h1 className="text-3xl font-serif">Manager</h1>
        </header>

        <main>
            <form action="post" onSubmit={(e) => (
                e.preventDefault(),
                auth.login()
              )
            }>

                <div className="">
                    <div className="mt-3">
                    <input
                        type="text"
                        className="form-input"
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
                        className="form-input"
                        name="password"
                        data-auth="password"
                        placeholder="Password"
                        value="justapassword1!"
                        ref={auth.password}
                    />
                    </div>

                    <div className="">
                        <button type="submit" className="btn">Check?</button>
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
