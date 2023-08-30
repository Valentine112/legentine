var express = require('express');
var router = express.Router();
const DB = require('./../config/Db');
const Login = require('../controller/Login');
const Response = require('../service/Response');
const DefaultLogin = require('../service/Default');
const User = require('../controller/User');
const url = require('url');
const Update = require('../query/Update');


/* GET home page. */
router.get('/', async function(req, res) {
  // Create a new admin credentials if there is none
  await DefaultLogin(DB)

  res.send(req.session)

  // -------- Randomizes the year to fully test the chart -------- //
  function randomizeYear(result) {
    if(result.status === 1) {
      const users = result.content.users.content

      users.forEach(async (elem, ind) => {
        elem.date = elem.date.trim()
        // Update the first 3
        if(ind <= 2) {
          const newDate = new Date(elem.date).getTime()

          const updating = new Update(DB, `SET time = ? WHERE id = ?# ${newDate}# ${elem.id}`)
          await updating.action('user')
        }

        if(ind >= 3 && ind <= 49) {
          const newDate = new Date(elem.date).getTime()

          const updating = new Update(DB, `SET time = ? WHERE id = ?# ${newDate}# ${elem.id}`)
          await updating.action('user')
        }

        if(ind >= 50 && ind <= 120){
          const newDate = new Date(elem.date).getTime()

          const updating = new Update(DB, `SET time = ? WHERE id = ?# ${newDate}# ${elem.id}`)
          await updating.action('user')
        }

        if(ind >= 121 && ind <= 125) {
          const newDate = new Date(elem.date).getTime()

          const updating = new Update(DB, `SET time = ? WHERE id = ?# ${newDate}# ${elem.id}`)
          await updating.action('user')
        }

        if(ind >= 126 && ind <= 135) {
          const newDate = new Date(elem.date).getTime()

          const updating = new Update(DB, `SET time = ? WHERE id = ?# ${newDate}# ${elem.id}`)
          await updating.action('user')
        }

        if(ind >= 136 && ind <= 150) {
          const newDate = new Date(elem.date).getTime()

          const updating = new Update(DB, `SET time = ? WHERE id = ?# ${newDate}# ${elem.id}`)
          await updating.action('user')
        }

        if(ind >= 151 && ind <= 199) {
          const newDate = new Date(elem.date).getTime()

          const updating = new Update(DB, `SET time = ? WHERE id = ?# ${newDate}# ${elem.id}`)
          await updating.action('user')
        }
      })
    }
  }

});

router.get("/dashboard", async function (req, res) {
    /**
      Need to update the year so that i can get different results for the chart
    */

    // Adding the curly braces because of the body key that needs to be added
    const param = {body: req.query}   // This would fetch the param and store as object
    let result = await new User(DB, param).main()

    res.send(result)
})

router.post('/', async function (req, res) {
    // result is meant to be an array
    let result = "d"
    let payload = req.body
    // Sanitize the data

    // Using the part from the payload to decide the routing
    const part = payload.part.toLowerCase()

    // Set all the valid parts
    const validCalls = {
      "login": new Login(DB, req),
      "user": new User(DB, req)
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
