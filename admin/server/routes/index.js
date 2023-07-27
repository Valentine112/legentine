var express = require('express');
var router = express.Router();
const DB = require('./../config/Db');
const Func = require('../service/Func');
const Login = require('../controller/Login');
const Response = require('../service/Response');
const DefaultLogin = require('../service/Default');


/* GET home page. */
router.get('/', async function(req, res, next) {
  // Create a new admin credentials if there is none
  await DefaultLogin(DB)
  console.log(req.session)
});

router.post('/', async function (req, res) {
    // result is meant to be an array
    let result = ""
    let payload = req.body
    // Sanitize the data

    // Using the part from the payload to decide the routing
    const part = payload.part.toLowerCase()

    // Set all the valid parts
    const validCalls = {
      "login": new Login(DB, req)
    }

    // Calling the class if it exist
    if(validCalls[part]) {
      // Call the class along with its main method
      // Every controller class uses the main method
      result = await validCalls[part].main()

    }else{
      // If class doesn't exist, return this message
      let response = new Response()

      response.status = 0
      response.type = "error"
      response.message = "void"
      response.content = "The path specified is not valid"

      result = response.deliver()
    }

  res.send(result)
})

module.exports = router;
