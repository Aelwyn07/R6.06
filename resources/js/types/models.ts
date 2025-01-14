/**
 * @interface MenuItem
 *
 * Représente un élément du menu.
 */
export interface MenuItem {
    /**
     * Le nom de la classe de l'icône.
     */
    iconClass: string;
    /**
     * Le label de l'élément du menu.
     */
    label: string;
    /**
     * La route de l'élément du menu.
     */
    route: string;
    /**
     * Le sous-menu de l'élément du menu.
     */
    submenu?: MenuItem[];
    /**
     * Indique si l'élément du menu est désactivé.
     */
    disable?: boolean;
}

/**
 * @interface Period
 *
 * Représente une période.
 */
export interface Period {
    /**
     * L'ID de la période.
     */
    id: number;
    /**
     * Le nom de la période.
     */
    name: string;
}

/**
 * @interface Item
 *
 * Représente un élément.
 */
export interface Item {
    /**
     * L'ID de l'élément.
     */
    id: number;
    /**
     * Le nom de l'élément.
     */
    name: string;
    /**
     * La période de l'élément.
     */
    period?: Period;
}

export enum EditedItemStatus {
    ADDED,
    DELETED,
    MODIFIED,
}

export interface EditedItem extends Item {
    /**
     * Indique si l'élément a été modifié.
     */
    editStatus: EditedItemStatus;
}

/**
 * @interface Teacher
 *
 * Représente un enseignant.
 */
export interface Teacher extends Item {
    /**
     * Le prénom de l'enseignant.
     */
    firstname: string;
    /**
     * Le nom de l'enseignant.
     */
    lastname: string;
    /**
     * Le code de l'enseignant.
     */
    code: string;
    /**
     * Les enseignements de l'enseignant.
     */
    teachings: Teaching[];
}

/**
 * @interface Teaching
 *
 * Représente un enseignement.
 */
export interface Teaching extends Item {
    /**
     * Le code apogee de l'enseignement.
     */
    apogee_code: string;
    /**
     * Le nombre initial de CM de l'enseignement.
     */
    initial_cm: number;
    /**
     * Le nombre initial de TD de l'enseignement.
     */
    initial_td: number;
    /**
     * Le nombre initial de TP de l'enseignement.
     */
    initial_tp: number;
    /**
     * Le nombre continu de CM de l'enseignement.
     */
    continuing_cm: number;
    /**
     * Le nombre continu de TD de l'enseignement.
     */
    continuing_td: number;
    /**
     * Le nombre continu de TP de l'enseignement.
     */
    continuing_tp: number;
    /**
     * Les enseignants de l'enseignement.
     */
    semester?: number;
    trimester?: number;
}

/**
 * @interface Class
 *
 * Représente une classe.
 */
export interface Class extends Item {
    groups: Group[];
}

/**
 * @interface Group
 *
 * Représente un groupe.
 */
export interface Group extends Item {
    subgroups: Subgroup[];
}

/**
 * @interface Subgroup
 *
 * Représente un sous-groupe.
 */
export interface Subgroup extends Item {}

export enum SlotType {
    CM = "CM",
    TD = "TD",
    TP = "TP",
}

/**
 * @interface CalendarContent
 *
 * Représente le contenu d'une cellule du calendrier.
 */
export interface CalendarContent {
    /**
     * Le nombre d'heures.
     */
    hours: number;
    /**
     * Le code de l'enseignant.
     */
    teacherCode: string;
    /**
     * Le type de cours (CM, TD, TP).
     */
    type: string;
    /**
     * Indique si le contenu est neutralisé.
     */
    isNeutralized: boolean;
}

/**
 * @interface CalendarGroup
 *
 * Représente un groupe dans le calendrier avec ses contenus et sous-groupes.
 */
export interface CalendarGroup {
    /**
     * Les contenus du groupe.
     */
    contents: CalendarContent[];
    /**
     * Les sous-groupes.
     */
    groups?: CalendarGroup[];
}

/**
 * @interface CalendarWeek
 *
 * Représente une semaine dans le calendrier.
 */
export interface CalendarWeek {
    /**
     * Le numéro de la semaine.
     */
    week: number;
    /**
     * Les groupes de la semaine.
     */
    groups: CalendarGroup[];
}

/**
 * @type Calendar
 *
 * Représente le calendrier complet.
 */
export type Calendar = CalendarWeek[];
