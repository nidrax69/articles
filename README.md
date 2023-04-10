# Zelty
Ce projet est une API Laravel pour gérer des articles.

## Dependencies
This project requires:

- PHP 8.2 or later
- Composer
- SQLite


## Installation

To install the project, follow these steps:

1. Clone the repository:
```sh
git clone https://github.com/nidrax69/zelty.git
```

2. Install the project:

Make sure to make the script executable with ``chmod +x install.sh`` 

```sh
./install.sh
```

If the tests are passed successfully, The application will be available at ``http://localhost:8000``

3. Configure Postman to consume the API

- Import the file to Postman :  ``Articles.postman_collection.json``
- Import the environment file to postman : ``Zelty.postman_environment.json``

4. Configure environment 

Set the environment to Zelty and modify it : 
- name `Your name for registering`
- email `Your email for registering/login`
- password `Your password for registering/login`
- access_token `to set up automatically the access_token variable after registering/login`
- base_url `If you have a different url where the serve is available`
