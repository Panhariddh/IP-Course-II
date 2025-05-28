import { Module } from '@nestjs/common';
import { TypeOrmModule } from '@nestjs/typeorm';
import { UserModule } from '../user/user.module';
import { Task } from './entity/task.entity';
import { TasksController } from './task.controller';
import { TaskService } from './task.service';
import { UserService } from '../user/user.service';

@Module({
  imports: [TypeOrmModule.forFeature([Task]), UserModule],
  controllers: [TasksController],
  providers: [TaskService, UserService],
  exports: [],
  // Add any other necessary configurations or modules
})
export class TaskModule {}
