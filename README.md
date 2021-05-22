************************************************                 *******************************************************
                                                API INTRODUCTION
************************************************                 *******************************************************

   This project is made on Laravel with Passport. Laravel Passport provides a full OAuth2 server implementation.
for your Laravel application. There are other option which can be used to provide token based authentication to API routes
like Sanctum. Most simple way is to create your own API Token and stored them in some comfig file i.e /config.
Why config file? Bacause we want DB hit for every API HIT to check the auth token. Then make a middleware which checks
for the auth token configured in the /config dir and allows further operations. Anyways, Let's have a look at our API's


**Always Put Accept:application/json into header before sending any request by postman.

Register user

POST /api/register

    parameters 
        name - string
        email - email
        passowrd - Must me more than 8. Mixed.
    Response
        {
            "token" : {{TOKEN}}
        }

Login API
POST /api/login

    parameters
        email - email id value while registering
        password - password that that user
    Response
        {
            "token" : {{TOKEN}}
        }

**Login response will give a token which needs to be sent during the passport secured route.
**In the request header, send this token as Bearer token to get acces to participants routes.


Get the List of Participants

GET /api/participants

    Query Options 
        searchName : Search a participant with their name
        searchLocality : Search participants by their searchLocality
        paginate : checks if request is for paginated result. It must be an int value ot it will be neglected.
    Header
        Authorization: Bearer {{TOKEN}}
    Response
        {
            "success": true,
            "data": {$participant_collection_object}
        }

Insert new participant

POST /api/participants

    Form Data
        name - name of participant
        age - age of participant (must be between 18-55). Integer value.
        date_of_birth : Date of bitrh in Y-m-d format.
        profession : Profession of participant. Either Employed or Student
        locality : Locality of the participant.
        guests : Number of guests coming along with participant. Must be int value less than 2.
        address: address of the participant. Char limit is 50.
    Header
        Authorization: Bearer {{TOKEN}}
    Response
        {
            "success": true,
            "data": {$participant_collection_object}
        }

Update existing participant

PUT /api/participants/{{id}}

    Form Data
        {{name_of_the_field}} : {{value of the field}}
    Response
        {
            "success": true
        }
