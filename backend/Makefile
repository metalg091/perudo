# Compiler
CC = g++
# Compiler flags
CFLAGS = -Wall
# Linker flags
LDFLAGS = -lssl -lcrypto -lz
# Source files
SRCS = main.cpp
# Object files
OBJS = $(SRCS:.cpp=.o)
# Output executable
TARGET = main.out

# Submodule directories
UWEBSOCKETS_DIR = uWebsockets
USOCKETS_DIR = $(UWEBSOCKETS_DIR)/uSockets

# Environment variable for submodules
WITH_OPENSSL = 1

all: $(TARGET)

# Rule to link the executable
$(TARGET): $(OBJS) $(USOCKETS_DIR)/libusockets.a
	$(CC) $(CFLAGS) -o $(TARGET) $(OBJS) $(USOCKETS_DIR)/*.o $(LDFLAGS)

# Rule to compile source files into object files
%.o: %.cpp
	$(CC) $(CFLAGS) -c $< -o $@

# Rule to build uWebsockets and uSockets
$(USOCKETS_DIR)/libusockets.a:
	WITH_OPENSSL=$(WITH_OPENSSL) $(MAKE) -C $(USOCKETS_DIR)

# Clean rule to remove generated files
clean:
	rm -f $(OBJS) $(TARGET)
	$(MAKE) -C $(USOCKETS_DIR) clean
