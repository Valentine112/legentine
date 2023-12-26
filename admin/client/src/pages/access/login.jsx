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
      <div className="w-6/12 text-center h-fit fixed bottom-0 top-0 right-0 left-0 m-auto">
        <header className="w-full">
          <h1 className="text-slate-50 text-5xl font-serif">Manager</h1>
        </header>

        <main className='py-10'>
            <form action="post" onSubmit={(e) => (
                e.preventDefault(),
                auth.login()
              )
            }>

                <div className="">
                    <div className="mt-3">
                    <input
                        type="text"
                        className="w-6/12 p-2 border border-dashed border-slate-50 bg-transparent text-slate-50"
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

