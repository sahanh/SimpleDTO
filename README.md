SimpleDTO
=========

Simple DTO object to move data. Requires PHP 5.4

###Practical Usage
When returning complex data, instead of returning an array DTOs can be returned.

    class UserService
    {
        public function getUsers()
        {
            $rows = $this->db->users->get();
            return DTO::make($rows);
        }
    }

View or Controllers can use DTO objects to access data. No need to worry about undefined index errors when using get method.

    {{ $data->get('name') }}

    @foreach($data->get('cars') as $car)
        <h1>{{ $car }}</h1>
    @endforeach

###Installing
Package can be installed via composer.

###Install Composer
    curl -sS https://getcomposer.org/installer | php

    Next, update your project's composer.json file to include EZCash:

    {
        "require": {
            "sahanh/simpledto": "~1.0"
        }
    }

###Making DTO Object
    use SH\SimpleDTO\DTO;
    
    $raw = [
        'id'      => 1,
        'name'    => 'John Doe',
        'email'   => 'john@doe.com',
        'address' => [
            'street' => '123 Main St.',
            'city'   => 'Wind'
        ],
        'cars'  => [
            'BMW-7',
            'Toyota Supra',
            'Nissan GT',
            'Jaguar'
        ]
    ];
    
    //pass an array or stdClass objects
    $dto = DTO::make($raw);

###Accessing Data
    //accessing properties
    echo $dto->name; //John Doe
    echo $dto->address->city; //Wind
    
    //accessing as an array
    echo $dto['name']; //John Doe
    echo $dto["address"]["city"] //Wind

####Using dot-notation
DTO's data can be accessed using get method, get method accept `.` for nested elements

    echo $dto->get('address.city');

One advantage of get is, data can be accessed without making sure if they exist or not.

    //returns NULL without errors, (no undefined index errors like in arrays)
    $dto->get('address.country');

###Iterating
For every nested array element data new DTO object will be created on retrival, and DTO object implements `IteratorAggregate` inteface.

    foreach ($dto->cars as $car) {
        echo $car;
    }

    //since `$dto->cars` is an array, `$dto->cars` will be returning another DTO object.
    $cars = $dto->cars;
    echo $cars->get(0); //accessing get() of DTO object

###JSON Serializing
Pass the DTO object to json_encode and get the data in json

    echo json_encode($dto);

###Write Protected
Data inside a DTO object cannot be modified once created making it perfect to move around.
    
    //RuntimeException - 'Data cannot be modified'
    $dto->new_value   = 'Some value';
    $dto['new_value'] = 'Some value';

---
Credits: The library uses illuminate/helpers package
