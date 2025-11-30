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

enum UserStatus: int {
    case INACTIVE = 0;
    case ACTIVE = 1;
}

/*
 * Enum para horários criados
 */
enum ScheduleStatus: int {
    case INACTIVE = 0;
    case ACTIVE = 1;
}

/*
 * Enum para reservas
 */
enum BookingStatus: int {
    case CONFIRMED = 0;
    case CANCELLED = 1;
}
