import 'package:hive_flutter/hive_flutter.dart';

class TaskStorage {
  List tasks = [];

  final _myTasksBox = Hive.box('myTasksBox');

  void createInitialData() {
    tasks = [
      ['Design a logo for Orama', false],
      ['Do my laundry', false],
      ['Write Isaac a quotation', false],
      ['Finish catalogue', false],
      ['Learn flutter', false],
    ];
  }

  void loadData() {
    tasks = _myTasksBox.get('TASKLIST');
  }

  void updateData() {
    _myTasksBox.put('TASKLIST', tasks);
  }
}
