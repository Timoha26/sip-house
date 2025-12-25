import {Component} from "@angular/core";
import {ProjectModel} from "../../models/project.model";
import {ProjectsService} from "../../services/projects.service";

@Component({
  templateUrl: 'projects.component.html'
})
export class ProjectsComponent {
  id: string | undefined;
  projects: ProjectModel[] = [];


  constructor(private projectsService: ProjectsService) {
  }

  private getProjects() {
    this.projectsService.get().subscribe({next: data => this.projects = data});
  }

  ngOnInit() {
    this.getProjects();
  }
}
