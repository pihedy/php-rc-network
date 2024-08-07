# PHP RC Network

The PHP RC Network is a PHP-based project designed to control remote-controlled (RC) devices. The main goal of the project is to provide a client application capable of communicating with and controlling various RC devices over a network.

## Key Features and Components

- **Serial Port Communication**: The project includes a `Serial` class that enables communication via the serial port. This is crucial for direct interaction with RC devices.
  
- **WebSocket Server**: The `ControlStartCommand` class initiates a WebSocket server, facilitating real-time, bidirectional communication between clients and the server.

- **Console Interface**: The project uses the Symfony Console component to provide a command-line interface. This allows the execution of various commands, such as starting the server or running test functions.

- **Configuration Management**: The `PhpFileConfig` class allows the project to load and manage configuration settings from PHP files.

- **Dependency Injection Container**: The project uses its own container implementation to manage services and dependencies.

- **Modular Architecture**: The project utilizes `Provider` classes to register various services, enhancing code modularity and maintainability.

## Project Structure

The structure of the project allows for flexible and efficient control of RC devices by combining serial communication with modern web technologies. This is ideal for applications where RC devices, such as robots, drones, or other remote-controlled vehicles, need to be controlled remotely.

## Hardware Configuration

The project has been specifically tested and optimized on a Raspberry Pi 3B model. This Raspberry Pi is directly connected to an Arduino Uno R3, which plays a crucial role in the system's operation. The Arduino Uno R3 acts as an intermediary between the Raspberry Pi and the Electronic Speed Controllers (ESCs).

- **Raspberry Pi 3B**: Runs the main control software.
- **Arduino Uno R3**: Manages low-level communication and control with the ESCs.

This hardware configuration enables efficient control and communication with RC devices. The Raspberry Pi provides greater processing capacity and flexibility, while the Arduino platform ensures precise timing and hardware interfacing. This setup is ideal for RC projects requiring enhanced processing power and flexibility, while maintaining the precise control capabilities offered by Arduino platforms.

## Conclusion

This hardware configuration perfectly aligns with the project's goals, allowing for the efficient and reliable control of complex RC devices.

---

For more details, please refer to the [TODO](https://www.youtube.com/watch?v=dQw4w9WgXcQ).

---

## License

This project is licensed under the MIT License. See the [LICENSE](https://github.com/pihedy/php-rc-network/blob/develop/LICENSE.md) file for details.

## Contact

For any questions or inquiries, please contact [pihedy@gmail.com](mailto:pihedy@gmail.com).

---

This `README.md` file provides an overview of the PHP RC Network project, outlining its key features, components, and hardware configuration. For detailed instructions and additional information, please refer to the project documentation.
