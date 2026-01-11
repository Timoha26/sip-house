import {ObjectTypesEnum} from "./objectTypes.enum";

export interface RequestModel {
  name?: string;
  phone?: string;
  email?: string;
  comment?: string;
  objectType: ObjectTypesEnum;
}
