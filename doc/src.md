# Classes, ...

    src/
    └── Yago

This is Yago namespace. All files, except interfaces, are locate here.

        ├── Building.php

Is a value object to get building seconds necessary to build the building.

        ├── BuildingTree.php

Encapsulate the logic, configurable via app/buildings.yml of the building tree.

        ├── Configuration.php

Load configuration.

        ├── DataLayer.php

Something between Yago and the database. The sense of this is to obtain a decoupling from the orm.

        ├── Interfaces
        │   ├── Clock.php
        │   ├── ConfigurationInterface.php
        │   ├── DataLayerInterface.php
        │   └── PlayerInterface.php

Bla bla bla.

        ├── Json.php

Bla bla!

        └── Player.php

Bla!

    2 directories, 10 files
