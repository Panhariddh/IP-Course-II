import { Injectable, NotFoundException } from '@nestjs/common';
import { InjectRepository } from '@nestjs/typeorm';
import { Repository } from 'typeorm';
import { Task } from './entity/task.entity';
import { CreateTaskDto } from './dto/create-task.dto';
import { UserService } from '../user/user.service';

@Injectable()
export class TaskService {
  constructor(
    @InjectRepository(Task)
    private tasksRepo: Repository<Task>,
    private userService: UserService, // Inject UserService to verify user existence
  ) {}

  async create(createTaskDto: CreateTaskDto) {
    // Verify user exists
    const user = await this.userService.findOne(createTaskDto.userId);
    if (!user) {
      throw new NotFoundException(`User with id ${createTaskDto.userId} not found`);
    }

    // Create new task
    const task = this.tasksRepo.create({
      name: createTaskDto.name,
      description: createTaskDto.description,
      user: { id: createTaskDto.userId },  // Assign user relationship
    });

    return this.tasksRepo.save(task);
  }

  findAll() {
    return this.tasksRepo.find({
      select: ['id', 'name', 'description', 'completedAt'],
      relations: ['user'],
    });
  }

  async findOne(id: number) {
    const task = await this.tasksRepo.findOne({
      where: { id },
      select: ['id', 'name', 'description', 'completedAt'],
      relations: ['user'],
    });

    if (!task) {
      throw new NotFoundException(`Task with id ${id} not found`);
    }
    return task;
  }


  async update(id: number, updateData: Partial<Task>) {
    await this.tasksRepo.update(id, updateData);
    return this.findOne(id);
  }

  remove(id: number) {
    return this.tasksRepo.delete(id);
  }
}
