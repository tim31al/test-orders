# test-orders

git clone git@github.com:tim31al/test-orders.git my_dir

cd my_dir

bash init.sh

## Routes

GET http://localhost:8080/orders[?limit=int[&offset=int]] - list orders

GET http://localhost:8080/orders/id - order

POST http://localhost:8080/orders - create order

PUT http://localhost:8080/orders/id - update order

DELETE http://localhost:8080/orders/id - delete order

POST, PUT payload
```
{
    "orderNumber": string,
    "lastname": string,
    "firstname": string,
    "price": int,
    "address": {
        "city": string,
        "street": string,
        ["apartment": int]
    }
}
```
