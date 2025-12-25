import {ImageModel} from "./image.model";

export interface ProjectModel {
  id: string;
  name: string;
  description: string;
  price: number;
  priceDescription: string;
  pdfUrl: string;
  images: ImageModel[];
}
