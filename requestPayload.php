<?php

$payload = <<<PAYLOAD
[  
   {  
      "api":1,
      "method":"auth",
      "params":{  
         "username":"...",
         "password":"..."
      },
      "id":1
   },
   {  
      "api":1,
      "method":"Product.createProducts",
      "params":{  
         "productsInfo":[  
            {  
               "approval_status":1,
               "attribute_set":"1",
               "attributes":{  
                  "4":"active",
                  "50":"10",
                  "55":"Supa Tex Marketplace",
                  "58":"...",
                  "66":"10",
                  "77":"Red",
                  "80":"Canon",
                  "140":"4",
                  "142":"0.00",
                  "143":"0.00",
                  "236":"active",
                  "248":"Canon",
                  "249":"Canon&nbsp;Canon"
               },
               "brand":"11",
               "categories":[  
                  "1264",
                  "1257",
                  "1199",
                  "490",
                  "1"
               ],
               "config_id":null,
               "description":"Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque interdum leo in feugiat aliquam. Cras suscipit lacinia orci. In vel velit pellentesque, maximus velit ut, aliquet velit. Etiam congue ipsum eu nisl vestibulum interdum. Maecenas ullamcorper in eros eget ornare. Mauris lorem lacus, feugiat quis enim non, tincidunt hendrerit lectus. Suspendisse porttitor, eros et consequat luctus, orci leo porta nisl, sit amet aliquam enim nibh nec lacus. Sed id laoreet urna, a hendrerit turpis. Sed tristique, nisl a gravida dapibus, dui lacus dignissim mi, in consectetur ligula ipsum pulvinar massa. Donec metus sapien, varius eu iaculis a, volutpat id justo. Pellentesque ut fringilla velit. Morbi sed lacus a turpis facilisis tincidunt quis id ex.

Etiam sit amet neque non sem vestibulum lobortis et eget enim. Aliquam erat volutpat. Integer ultricies, nibh ut ullamcorper maximus, odio libero ornare nisl, id lacinia purus justo non mi. Suspendisse luctus sem id feugiat aliquet. Nunc lacinia lacus non blandit elementum. Aliquam erat volutpat. Donec quis lorem eu tellus luctus tempor. Vivamus at aliquet nibh, sit amet dignissim ligula. Ut quis molestie eros, non venenatis eros. Proin elementum nibh nec diam convallis aliquet. Aliquam sed iaculis tortor, eu placerat risus. Integer fermentum interdum lobortis. Maecenas sit amet nunc ut arcu lacinia hendrerit non ac libero.

Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Sed mattis risus nec tellus congue, eget aliquam elit interdum. Praesent mattis egestas eros, et ultricies augue accumsan sed. Quisque at nunc sed dolor efficitur dignissim eget eget neque. Quisque sit amet risus sagittis, tincidunt massa nec, scelerisque felis. Ut imperdiet urna nec pulvinar facilisis. Integer vel erat augue. Phasellus ultricies venenatis felis, in convallis magna tristique eget. Donec commodo lobortis ex, nec elementum est finibus non. Quisque eu ligula imperdiet, aliquam augue quis, blandit diam. Ut blandit vehicula dui ut scelerisque. Maecenas a sapien quis eros malesuada consectetur quis sit amet est.

Fusce blandit pellentesque nunc, a efficitur urna cursus in. Praesent vulputate, magna at tincidunt gravida, ipsum odio pharetra libero, eu rutrum urna sapien nec justo. Praesent cursus placerat turpis, ut faucibus purus pulvinar vel. Curabitur sed tellus nec lectus sagittis facilisis. Aenean maximus a leo eget consequat. Fusce tempor, libero a porttitor gravida, urna nulla consectetur mi, a auctor turpis justo at nunc. In sed maximus erat.",
               "platform_identifier":"SellerCenter",
               "price":"100.00",
               "product_identifier":"Canon",
               "product_set":"90222",
               "seller":"624",
               "seller_sku":"Canon",
               "shipment_type":"2",
               "special_from_date":null,
               "special_price":null,
               "special_to_date":null,
               "status":"active",
               "stock":5000,
               "tax_class":"1",
               "title":"Canon",
               "id_catalog_product":"174301",
               "shipment_matrix_template":[  

               ]
            }
         ]
      },
      "id":1
   }
]
PAYLOAD;

return $payload;