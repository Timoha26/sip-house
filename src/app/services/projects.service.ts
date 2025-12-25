import {Injectable} from "@angular/core";
import {Observable} from "rxjs";
import {ProjectModel} from "../models/project.model";
import {HttpClient} from "@angular/common/http";

@Injectable()
export class ProjectsService {
  constructor(private http: HttpClient) {
  }

  public get(): Observable<ProjectModel[]> {
    return this.http.get<ProjectModel[]>('assets/projects.json');
  };
}
