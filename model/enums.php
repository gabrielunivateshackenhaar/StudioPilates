<?php
// model/Enums.php
// Enums para campos de Users

enum Gender: int {
    case MALE = 0;
    case FEMALE = 1;
}

enum Laterality: int {
    case RIGHT = 0;
    case LEFT = 1;
    case AMBIDEXTROUS = 2;
}

enum Category: int {
    case NORMAL = 0;
    case ADMIN = 1;
}
