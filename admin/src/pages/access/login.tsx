import { FormEvent, useState } from "react";
import "../css/access/login.css";
import FormAuth from "../../services/FormAuth";

function Login() {
    let [formData, setFormData] = useState({})

    function getForm(ev: {target: any}) {
        var element = ev.target
        var elemName = element.name
        var elemAuth = element.dataset.auth

        // Delete any key whose value is empty
        if(element.value.length < 1){
            setFormData(prev => {
                const newData = { ...prev }
                delete newData[elemName as keyof typeof newData]

                return newData
            })
        }
        else{
            setFormData(prev => {
                return(
                    {
                        ...prev,
                        [elemName]: {
                            value: element.value,
                            auth: elemAuth
                        }
                    }
                )
            })
        }

    }

    function submitForm(e: FormEvent<HTMLFormElement>) {
        e.preventDefault()
        // Call the formAuth to crosscheck the form

        // The required should be in descending on who is been attended to first
        var formAuth = new FormAuth(formData, ["password", "username"])
        var result = formAuth.process()

        result.then(token => {
            console.log(token)
        })
    }

  return (
    <div>
      <div className="container">
        <header className="mx-auto col-12 text-center mt-5">
          <h1 className="text-center">Manager</h1>
        </header>
        <main>
            <form action="post" onSubmit={(e) => (
                    submitForm(e)
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
                        onChange={getForm}
                    />
                    </div>
                    <div className="mt-3">
                    <input
                        type="text"
                        className="form-control"
                        name="password"
                        data-auth="password"
                        placeholder="Password"
                        onChange={getForm}
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
