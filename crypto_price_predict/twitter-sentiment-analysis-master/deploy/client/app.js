const express = require('express')
const cors = require('cors')
const app = express()
const port = 4000

app.use(cors())
app.set('views', './views')
app.set('view engine', 'pug')

app.use(express.static(__dirname + '/public'));

app.get('/', (req, res) => res.render('week'))

app.listen(port, () => console.log(`Example app listening on port ${port}!`))