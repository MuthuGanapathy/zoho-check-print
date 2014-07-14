Zoho Books Check Print
=========

This is a standalone plugin for printing checks on stock paper with Zoho Books integration.

  - Multiple templates with JSON
  - Multiple Zoho Books organizations
  - No database required
  - Lightweight < 30kb
 

> Zoho Books does not provide a check printing feature which prevented our team from using this solution for one of our partners. We decided to use the Zoho Books API and create our own standalone solution that supported multiple check stocks and organizations within Zoho Books.

  
Getting Started
----
To begin using the plugin, clone the git repository and upload the files to a web server with PHP installed. This project does not require a database; instead you must edit the `config.json` file in the project root.

```sh
{
    "organizations":{
        "Zoho Org Name 1":"YOUR_ZOHO_ORG_1_ID",
        "Zoho Org Name 2":"YOUR_ZOHO_ORG_2_ID"
    },
    "auth":{
        "token":"ZOHO_AUTH_TOKEN"
    }
}
```

Check Templates
-----------

To create a check template, open the `templates/example.json`

```sh
{
    "template":{
      "name":"example"
    },

    "fields":{
        "payee":{
            "left":"13%",
            "top":"12.8%",
            "font-size":"12px"
        },

        "memo":{
            "left":"10%",
            "top":"25.6%"
        },

        "amount":{
            "left":"86%",
            "top":"13%",
            "css":"color:green;font-weight:bold"
        },

        "written-amount":{
            "left":"10%",
            "top":"16%"
        },

        "date":{
            "left":"86%",
            "top":"9%",
            "format":"m/d/Y"
        }
    },

    "check":{
        "font-family":"Courier New",
        "font-size":"14px",
        "padding":"20px"
    }
}
```
The template's `name` must match the name of the template JSON file. I.E. The template name for example.json should be "example". 

Each field has a `left` and `top` option and are both required. These are used to aboslute position the check elements on your stock paper. This may required some tweaking to get perfect. We suggest test printing on blank paper and using a light source to compare the alignement with the original stock paper.

You may additionally specify a `font-size` for any of the fields individually. If you need more control over the fields, you may use `css` to provide any additional CSS rules.


License
----

Copyright 2014 Hook Global, LLC

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at

http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.

